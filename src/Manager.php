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
        $fullEntity = "App\\".$entity;
        $repo = new $fullEntity($this->connection);

        return $repo;
    }
}
