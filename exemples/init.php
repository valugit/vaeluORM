<?php

use VaeluORM\Manager;
use Symfony\Component\Yaml\Yaml;

require_once "vendor/autoload.php";

$config = Yaml::parseFile('../config/config.yml');

$dsn = "mysql:host=".$config['db']['host'].";port=".$config['db']['port'].";dbname=" . $config['db']['name'];
$user = $config['db']['user'];
$pwd = $config['db']['password'];
$conn = new PDO($dsn, $user, $pwd);

$entities = new Manager();
