<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

//new
$tea = new BubbleTea();

$tea->set('tea', 'green');
$tea->set('flavor', 'rose');
$tea->set('poppings', 'lemon');
$tea->set('size', 500);
$tea->set('hot', 0);

$TeaRepo->save($tea);
echo "New bubble tea created : " . $tea->getId() . "\n";
