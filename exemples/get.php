<?php

require_once('init.php');

$TeaRepo = $entities->getEntity('Tea');

//get
$idTea = $TeaRepo->getOneBy('id', '404');
$teaFlavor = $idTea->flavor; // ->__get('flavor');
echo $teaFlavor;


//new
$tea = new BubbleTea();

$tea->set('tea', 'green');
$tea->set('flavor', 'lemon');
$tea->set('poppings', 'green apple');
$tea->set('size', 700);

$entityManager->persist($tea);
$entityManager->flush();
echo "New bubble tea created : " . $tea->getId() . "\n";

//all
$allTeas = $TeaRepo->getAll();

//filter
$greenTeas = $TeaRepo->getAllBy('tea', 'green');
