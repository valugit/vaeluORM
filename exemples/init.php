<?php

use VaeluORM\Manager;
use Symfony\Component\Yaml\Yaml;

require_once "vendor/autoload.php";

$config = Yaml::parseFile(__DIR__.'/../config/config.yaml');

var_dump($config);

$dsn = "mysql:host=" . $config['database']['host'].";port=" . $config['database']['port'].";dbname=" . $config['database']['name'];
$user = $config['database']['user'];
$pwd = $config['database']['password'];
$conn = new PDO($dsn, $user, $pwd);

$entities = new Manager($conn);
