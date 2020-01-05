<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

//get
$idTea = $TeaRepo->getOneBy('id', '1');
$teaTea = $idTea->tea; // ->__get('tea');
echo "Tea : " . $teaTea . "\n";
echo "Tea : " . $idTea->getId() . "\n";

//all
$allTeas = $TeaRepo->getAll(["limit" => 4, "orderby" => "flavor", "order" => "DESC"]);
echo "Elements in \$allTeas : " . count($allTeas) . "\n";
foreach ($allTeas as $tea) {
    echo "Tea flavor : " . $tea->flavor . "\n";
}

//filter
$greenTeas = $TeaRepo->getAllBy(["where" => ["tea" => "green"], "limit" => 2, "orderby" => "flavor"]);
echo "Elements in \$greenTeas : " . count($greenTeas) . "\n";
foreach ($greenTeas as $tea) {
    echo "Tea : " . $tea->tea . ", flavor : " . $tea->flavor . ", poppings : " . $tea->poppings . "\n";
}

//count
$blackTeaQuantitea = $TeaRepo->count(["where" => ["tea" => "black"]]);
echo "There is currently " . $blackTeaQuantitea . " black tea bubbleteas, in the database\n";

// exists
$whiteTeas = $TeaRepo->exists(["tea" => "white"]) ? "true" : "false";
echo "Someone ordered a white bubble tea : " . $whiteTeas . "\n";

$lemonTeas = $TeaRepo->exists(["flavor" => "lemon"]) ? "true" : "false";
echo "Someone ordered a lemon bubble tea : " . $lemonTeas . "\n";
