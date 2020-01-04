<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

//get
$idTea = $TeaRepo->getOneBy('id', '1');
$teaFlavor = $idTea->flavor; // ->__get('flavor');
echo $teaFlavor;

//all
$allTeas = $TeaRepo->getAll();
// foreach ($allTeas as $tea) {
//     echo $tea->flavor;
// }

// //filter
// $greenTeas = $TeaRepo->getAllBy('tea', 'green');
// foreach ($greenTeas as $tea) {
//     echo $tea->flavor;
// }
