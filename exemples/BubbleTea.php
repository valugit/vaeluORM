<?php

namespace App;

use VaeluORM\BaseEntity;

class BubbleTea extends BaseEntity
{
    protected $entityName;
    protected $table;
    protected $columns;

    public function __construct($connection="")
    {
        $this->entityName = 'BubbleTea';
        $this->table = 'bubble_tea';
        $this->columns = [
            'tea'=>'str',
            'flavor'=>'str',
            'poppings'=>'str',
            'size'=>'int',
            'hot'=>'bool'
        ];
        parent::__construct($connection, $this->columns);
    }
}
