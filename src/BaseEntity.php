<?php

namespace VaeluORM;

class BaseEntity
{
    private $connection;
    private $tempData;
    private $id;

    public function __construct($connection)
    {
        $this->connection = $connection;
        if (!file_exists("log")) {
            mkdir("log");
        }
    }

    public function tableExists($dbname) {
        $query = "SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = '".$dbname."' AND table_name = '".$this->getTableName()."' LIMIT 1";

        $statement = $this->connection->prepare($query);
        $statement->execute();

        $result = $statement->fetchObject();
        return $result;
    }

    public function query($query)
    {
        if (!is_string($query) || empty($query)) {
            $this->errorLog($query, "Query not valid");
            return;
        }

        $requestStart = microtime(true);

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $error = $statement->errorInfo();

        if ($error[0] != 0) {
            $this->errorLog($query, $error[0] . " : " . $error[2]);
        } else {
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            $requestEnd = microtime(true);
            $duration = $requestEnd - $requestStart;

            $this->log($query, $duration);

            if (empty($result)) {
                return;
            }

            if (!array_key_exists("TABLE_NAME", $result[0])) {

                if (array_key_exists("COUNT(*)", $result[0])) {
                    return $result[0]["COUNT(*)"];
                } else if (count($result) == 1) {
                    return $this->buildEntity($result[0]);
                } else {
                    $entities = array();

                    foreach ($result as $key => $value) {
                        $entities[] = $this->buildEntity($value);
                        // array_push($entities, $this->buildEntity($value));
                    }
                    return $entities;
                }
            }
        }
    }

    public function buildEntity($data)
    {
        $fullEntity = "App\\".$this->entityName;
        $entity = new $fullEntity();

        foreach ($data as $column => $value) {
            $entity->$column = $value;
        }

        return $entity;
    }

    public function getTableName()
    {
        return $this->table;
    }

    public function createTable()
    {
        $query = "CREATE TABLE " . $this->table . " (";
        $query .= "id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";
        foreach ($this->columns as $name => $type) {
            $query .= $name . " ";

            switch ($type) {
                case 'str':
                    $query .= "VARCHAR(255)";
                    break;
                case 'text':
                    $query .= "TEXT";
                    break;
                case 'int':
                    $query .= "INT";
                    break;
                case 'bool':
                    $query .= "TINYINT(1)";
                    break;

                default:
                    $query .= "VARCHAR(255)";
                    break;
            }
            $query .= ",";
        }
        $query = substr($query, 0, -1);
        $query .= ")";

        $this->query($query);
    }

    public function set($column, $value)
    {
        if (array_key_exists($column, $this->columns)) {
            $this->tempData[$column] = $value;
        } else {
            echo "This column does not exist : ".$column."\n";
        }
    }

    public function getOneBy($column, $value)
    {
        $query = "SELECT * FROM " . $this->getTableName();
        $query .= " WHERE " . $column . " = '" . $value . "'";
        $query .= " LIMIT 1";

        return $this->query($query);
    }

    public function getAll($params = [])
    {
        $query = "SELECT * FROM " . $this->getTableName();

        if (key_exists("orderby", $params)) {
            $query .= " ORDER BY " . $params["orderby"] . " ";
            $query .= key_exists("order", $params) ? $params["order"] : "ASC";
        }

        if (key_exists("limit", $params)) {
            $query .= " LIMIT " . $params["limit"];
        }

        return $this->query($query);
    }

    public function getAllBy($params = [])
    {
        $query = "SELECT * FROM " . $this->getTableName();

        if (key_exists("where", $params)) {
            foreach ($params["where"] as $column => $value) {
                $query .= " WHERE " . $column . " = '" . $value . "'";
            }
        }

        if (key_exists("orderby", $params)) {
            $query .= " ORDER BY " . $params["orderby"] . " ";
            $query .= key_exists("order", $params) ? $params["order"] : "ASC";
        }

        if (key_exists("limit", $params)) {
            $query .= " LIMIT " . $params["limit"];
        }

        return $this->query($query);
    }

    public function count($params = [])
    {
        $query = "SELECT COUNT(*) FROM " . $this->getTableName();

        if (key_exists("where", $params)) {
            foreach ($params["where"] as $column => $value) {
                $query .= " WHERE " . $column . " = '" . $value . "'";
            }
        }

        if (key_exists("limit", $params)) {
            $query .= " LIMIT " . $params["limit"];
        }

        return $this->query($query);
    }

    public function exists($where = [])
    {
        $query = "SELECT * FROM " . $this->getTableName();

        foreach ($where as $column => $value) {
            $query .= " WHERE " . $column . " = '" . $value . "'";
        }

        return !empty($this->query($query));
    }

    public function save($row, $replace=null)
    {
        if (is_int($replace)) {
            # update
            $query = "UPDATE ". $this->getTableName() . " SET ";
            foreach ($row->tempData as $key => $value) {
                $query .= $key . " = '" . $value . "', ";
            }
            $query = substr($query, 0, -2);
            $query .= " WHERE id = '" . $replace . "'";
        } else {
            #create
            $query = "INSERT INTO ".$this->getTableName()." (";
            $query .= implode(", ", array_keys($row->tempData));
            $query .= ") VALUES ('";
            $query .= implode("', '", $row->tempData);
            $query .= "')";
        }
        $this->query($query);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->getTableName();
        $query .= " WHERE id = " . $id;

        $this->query($query);
    }

    private function log($query, $duration) {
        // (time, query, parmeters, duration)
        $time = new \Datetime();
        $logText = "[" . $time->format('Y-m-d H:i:s') . "] : ";
        $logText .= $query;
        $logText .= " (in " . $duration . " seconds)";

        file_put_contents("log/log.txt", $logText.PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    private function errorLog($query, $error) {
        // (time, query, parameters, error)
        $time = new \Datetime();
        $logText = "[" . $time->format('Y-m-d H:i:s') . "] : ";
        $logText .= $query;
        $logText .= "\n    ERROR : " . $error;

        file_put_contents("log/error.txt", $logText.PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
