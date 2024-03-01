<?php

namespace App\Core;

use PDO;
use PDOException;

use App\Controllers\installer;

class DB
{
    private ?object $pdo = null;
    private static $instance = null;

    public function __construct()
    {
        $dsn = new installer();

        try {
            $this->pdo = new PDO($dsn->getDsnFromDbType(DB_TYPE), DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            die();
        }
    }

    public function exec(string $query): bool
    {
        try {
            $statement = $this->pdo->prepare($query);
            return $statement->execute();
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }
}

