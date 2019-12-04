<?php

use VaeluORM\Manager;
use Symfony\Component\Yaml\Yaml;

require_once "vendor/autoload.php";

$config = Yaml::parseFile('../config/config.yml');

$dsn = "mysql:host=".$params['db']['host'].";port=".$params['db']['host'].";dbname=" . $params['db']['name'];
$user = $params['db']['user'];
$pwd = $params['db']['password'];
$conn = new PDO($dsn, $user, $pwd);

$entities = new Manager();
