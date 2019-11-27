<?php

require_once('init.php');

$TeaRepo = $entities->getEntity('Tea');

//get
$idTea = $TeaRepo->getOneBy('id', '404');
$teaFlavor = $idTea->flavor; // ->__get('flavor');
echo $teaFlavor;


//new


//all
$allTeas = $TeaRepo->getAll();

//filter
$greenTeas = $TeaRepo->getAllBy('tea', 'green');
