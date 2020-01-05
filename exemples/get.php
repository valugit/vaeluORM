<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

//get
$idTea = $TeaRepo->getOneBy('id', '1');
$teaTea = $idTea->tea; // ->__get('tea');
echo "Tea : " . $teaTea . "\n";

//all
$allTeas = $TeaRepo->getAll();
foreach ($allTeas as $tea) {
    echo "Tea flavor : " . $tea->flavor . "\n";
}

//filter
$greenTeas = $TeaRepo->getAllBy($where = ["tea" => "green"]);
foreach ($greenTeas as $tea) {
    echo "Tea : " . $tea->tea . ", Tea flavor : " . $tea->flavor . "\n";
}
