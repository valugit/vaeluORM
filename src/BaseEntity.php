<?php

namespace VaeluORM;

class BaseEntity
{
    private $connection;
    private $data;
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

    public function fillData($data)
    {
        $this->data = $data;
    }

    public function getTableName()
    {
        return $this->table;
    }

    public function createTable($name)
    {
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
            $query = "UPDATE ".$this->getTableName()."SET ";
            foreach ($row->tempData as $key => $value) {
                $query .= $key." = ".$value.",";
            }
            $query = substr($query, 0, -2);
            $query .= " WHERE `".$this->getTableName()."`.`id` = ".$replace;
        } else {
            #create
            $query = "INSERT INTO ".$this->getTableName()." (`";
            $query .= implode("`, `", array_keys($row->tempData));
            $query .= "`) VALUES (";

            $newData = [];
            foreach ($row->tempData as $key => $value) {
                switch (gettype($value)) {
                    case 'boolean':
                        $newData[$key] = $value ? 1 : 0;
                        break;
                    case 'string':
                        $newData[$key] = "`".$value."`";
                        break;
                    case 'array':
                        throw new Exception("Cannot push array in mysql.", 1);
                        break;
                    case 'object':
                        throw new Exception("Cannot push object in mysql.", 1);
                        break;

                    default:
                    $newData[$key] = $value;
                        break;
                }
            }

            $query .= implode(", ", $newData);
            $query .= ")";
        }

        $this->query($query);
    }

    // get attribute of object
    public function __get($column)
    {
    }
}
