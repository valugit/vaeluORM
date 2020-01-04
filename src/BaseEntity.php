<?php

namespace VaeluORM;

class BaseEntity
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function query($query)
    {
        if (!is_string($query) || empty($query)) {
            throw new Exception("Query not valid");
        }
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        
        if ($error[0] != 0) {
            return false;
        } else {
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $result;
        }
    }

    public function createTable($name)
    {
    }
    
    public function createColumn($table, $name)
    {
    }

    public function set($column, $value)
    {
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

    // get attribute of object
    public function __get($column)
    {
    }
}
