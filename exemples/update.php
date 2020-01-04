<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

//new
$tea = new BubbleTea();

$tea->set('tea', 'black');
$tea->set('flavor', 'lemon');
$tea->set('poppings', 'green apple');
$tea->set('size', 900);
$tea->set('hot', 1);

$TeaRepo->save($tea, 4);
