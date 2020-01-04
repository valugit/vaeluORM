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

        $statement = $this->connection->prepare("SELECT * FROM ".$repo->getTableName());
        $statement->execute();
        $data = $statement->fetchObject();

        $repo->fillData($data); // put everything in $data which should be in baseEntity
        // $repo->createTable($repo->getTableName());
        return $repo;
    }
}
