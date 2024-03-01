<?php
// Database.php

namespace App\Core;

use PDO;
use PDOException;

class DB
{
    private static $instance = null;

    private function __construct() {
        try {
            self::$instance = new PDO(DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT, DB_USER, DB_PASSWORD);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }
}

