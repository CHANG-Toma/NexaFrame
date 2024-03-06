<?php

namespace App\Controllers;

class Dashboard
{

    public function __construct() {}

    public function index()
    {

        $components = [
            'sidebar.php',
        ];

        switch ($_SERVER['REQUEST_URI']) {
            case '/dashboard/page-builder':
                $components[] = 'page_builder.php';
                break;
            case '/dashboard/template':
                $components[] = 'template.php';
                break;
            case '/dashboard/comment':
                $components[] = 'comments.php';
                break;
            case '/dashboard/user':
                $components[] = 'user-data.php';
                break;
            default:
                $components[] = 'page_builder.php';
                break;
        }
        include __DIR__ . '/../Views/back-office/dashboard/index.php';
    }
}