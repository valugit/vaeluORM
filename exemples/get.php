<?php

require_once('init.php');

$TeaRepo = $manager->getEntity('BubbleTea');

//get
$idTea = $TeaRepo->getOneBy('id', '1');
$teaFlavor = $idTea->flavor; // ->__get('flavor');
echo $teaFlavor;


//new
$tea = new BubbleTea();

$tea->set('tea', 'green');
$tea->set('flavor', 'lemon');
$tea->set('poppings', 'green apple');
$tea->set('size', 700);

$TeaRepo->save($tea);
echo "New bubble tea created : " . $tea->getId() . "\n";

//all
$allTeas = $TeaRepo->getAll();
foreach ($allTeas as $tea) {
    echo $tea->flavor;
}

//filter
$greenTeas = $TeaRepo->getAllBy('tea', 'green');
foreach ($greenTeas as $tea) {
    echo $tea->flavor;
}
