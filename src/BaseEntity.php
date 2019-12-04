<?php

namespace VaeluORM;

class BaseEntity
{
    private $connection;

    public function __construct()
    {
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

    public function __get($column)
    {
    }
}
