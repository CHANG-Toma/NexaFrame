<?php

namespace App\Controllers;

class home
{
    public function __construct()
    {
        define('BASE_DIR', __DIR__ . '/..');
    }

    public function index(): void
    {
        include __DIR__ . '/../Views/front-office/main/home.php';
    }
}
