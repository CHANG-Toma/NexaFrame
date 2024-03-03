<?php

namespace App\Controllers;

class Dashboard
{

    public function __construct() {}

    public function index()
    {
        include __DIR__ . '/../Views/back-office/dashboard/dashboard.php';
    }

}