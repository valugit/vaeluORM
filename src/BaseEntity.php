<?php

namespace VaeluORM;

class BaseEntity
{
    private $connection;
    private $tempData;

    public function __construct($connection)
    {
        $this->connection = $connection;
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
            $result = $statement->fetchObject();
            return $result;
        }
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
            $query .= " NOT NULL,";
        }
        $query = substr($query, 0, -1);
        $query .= ")";

        $this->query($query);
    }

    public function createColumn($table, $name)
    {
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
    }

    public function getAll($limit = 0)
    {
    }

    public function getAllBy($column, $value, $limit = 0)
    {
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
            var_dump($query);
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

    // get attribute of object
    public function __get($column)
    {
    }
}
