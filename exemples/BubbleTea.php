<?php

namespace App;

use VaeluORM\BaseEntity;

class BubbleTea extends BaseEntity
{
    protected $table;
    protected $columns;

    public function __construct()
    {
        $this->table = 'bubble_tea';
        $this->columns = [
            'tea'=>'str',
            'flavor'=>'str',
            'poppings'=>'str',
            'size'=>'int',
            'hot'=>'bool'
        ];
    }
}
