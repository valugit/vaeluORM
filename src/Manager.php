<?php

namespace VaeluORM;

class Manager
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getEntity($entity)
    {
        //hard :(

        $repo = new $entity($this->connection);

        if ($repo->query("SELECT * FROM".$repo->getTableName())) {
            $repo->createTable($repo->getTableName())
        } else {
            $tableData = // execute request to get everything
            $repo->fillData($tableData) // put everything in $data which should be in baseEntity 
        }
    }
}
