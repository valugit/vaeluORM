<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

//new
$tea = new BubbleTea();

$tea->set('tea', 'green');
$tea->set('flavor', 'kiwi');
$tea->set('poppings', 'tapioca');
$tea->set('size', 700);
$tea->set('hot', 1);

$TeaRepo->save($tea, 5);
echo "Bubble tea successfully updated : " . $tea->id . "\n";
