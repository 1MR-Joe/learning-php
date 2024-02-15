<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$connectionParams = [
    'dbname' => $_ENV['DB_DATABASE'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'host' => $_ENV['DB_HOST'],
    'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
];
$conn = DriverManager::getConnection($connectionParams);


$ids = [100, 23, 155, 422];
$result = $conn->executeQuery(
    'SELECT * FROM city AS c WHERE c.id IN (?)',
    [$ids],
    [ArrayParameterType::INTEGER]
);

var_dump($result->fetchAllAssociative());


