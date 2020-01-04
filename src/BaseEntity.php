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

        try {
            $statement = $this->connection->prepare($query);
        } catch(PDOException $e) {
            echo "it doesn't work !";
            return;
        }

        $statement->execute();

        if ($error[0] != 0) {
            return false;
        } else {
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            return $result;
        }
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
