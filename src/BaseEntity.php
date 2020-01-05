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
            throw new \Exception("Query not valid");
        }

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $error = $statement->errorInfo();

        if ($error[0] != 0) {
            throw new \Exception("Something went wrong with the query : " . $error[0], 1);
        } else {
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($result)) {
                return;
            }

            if (!array_key_exists("TABLE_NAME", $result[0])) {

                if (count($result) == 1) {
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

    public function getAll($limit = 0, $orderby = "", $order = "ASC")
    {
        $query = "SELECT * FROM " . $this->getTableName();

        if ($limit != 0) {
            $query .= " LIMIT " . $limit;
        }
        if ($orderby != "") {
            $query .= " ORDER BY " . $orderby . " " . $order;
        }

        return $this->query($query);
    }

    public function getAllBy($where = [], $limit = 0, $orderby = "", $order = "ASC")
    {
        $query = "SELECT * FROM " . $this->getTableName();

        if ($where != []) {
            foreach ($where as $column => $value) {
                $query .= " WHERE " . $column . " = '" . $value . "'";
            }
        }

        if ($limit != 0) {
            $query .= " LIMIT " . $limit;
        }
        if ($orderby != "") {
            $query .= " ORDER BY " . $orderby . " " . $order;
        }

        return $this->query($query);
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
}
