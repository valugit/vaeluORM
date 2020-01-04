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

        $tableExists = $repo->query("SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = '".$this->dbname."' AND table_name = '".$repo->getTableName()."' LIMIT 1");

        if (!$tableExists) {
            $repo->createTable();
        }

        return $repo;
    }
}
