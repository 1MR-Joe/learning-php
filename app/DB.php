<?php

declare(strict_types = 1);

namespace App;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PDO;

/**
 * @mixin PDO
 */
class DB
{
    private Connection $conn;

    public function __construct(array $config)
    {   
        $this->conn = DriverManager::getConnection($config);
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->conn, $name], $arguments);
    }
}
