<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

//get
$idTea = $TeaRepo->getOneBy('id', '1');
$teaTea = $idTea->tea; // ->__get('tea');
echo "Tea : " . $teaTea . "\n";

//all
$allTeas = $TeaRepo->getAll(["limit" => 4, "orderby" => "flavor", "order" => "DESC"]);
foreach ($allTeas as $tea) {
    echo "Tea flavor : " . $tea->flavor . "\n";
}

//filter
$greenTeas = $TeaRepo->getAllBy(["where" => ["tea" => "green"], "limit" => 2, "orderby" => "flavor"]);
foreach ($greenTeas as $tea) {
    echo "Tea : " . $tea->tea . ", flavor : " . $tea->flavor . ", poppings : " . $tea->poppings . "\n";
}
