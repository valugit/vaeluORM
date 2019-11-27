<?php

$TeaRepo = $entities->$getEntity('Tea');

$allTeas = $TeaRepo->getAll();
$greenTeas = $TeaRepo->getAllBy('tea', 'green');
$idTea = $TeaRepo->getOneBy('id', '404');

$teaFlavor = $idTea->getAttribute('flavor');