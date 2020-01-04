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

    // public function query($query)
    // {
    //     if (!is_string($query) || empty($query)) {
    //         throw new Exception("Query not valid");
    //     }

    //     try {
    //         $statement = $this->connection->prepare($query);
    //     } catch(PDOException $e) {
    //         echo "it doesn't work !";
    //         return;
    //     }

    //     $statement->execute();

    //     if ($error[0] != 0) {
    //         return false;
    //     } else {
    //         $result = $statement->fetchAll(PDO::FETCH_OBJ);
    //         return $result;
    //     }
    // }

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

    public function save($row)
    {
    }

    // get attribute of object
    public function __get($column)
    {
    }
}
