<?php

namespace App\Controllers;

class Home
{
    public function __construct()
    {
        define('BASE_DIR', __DIR__ . '/..');
    }

    public function index(): void
    {
        include __DIR__ . '/../views/front-office/main/home.php';
    }
}
