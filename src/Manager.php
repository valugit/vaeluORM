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
        $fullEntity = "App\\".$entity;
        $repo = new $fullEntity($this->connection);

        $data = $repo->query("SELECT * FROM ".$repo->getTableName());
        $repo->fillData($data); // put everything in $data which should be in baseEntity
        // $repo->createTable($repo->getTableName());
    }
}
