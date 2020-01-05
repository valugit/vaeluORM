<?php

namespace VaeluORM;

class Manager
{
    private $connection;
    private $dbname;

    public function __construct($connection, $dbname)
    {
        $this->connection = $connection;
        $this->dbname = $dbname;
    }

    public function getEntity($entity)
    {
        $fullEntity = "App\\".$entity;
        $repo = new $fullEntity($this->connection);

        $tableExists = $repo->tableExists($this->dbname);

        if (!$tableExists) {
            $repo->createTable();
        }

        return $repo;
    }
}
