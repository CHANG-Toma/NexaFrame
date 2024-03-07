<?php

namespace App\Controllers;

use App\Controllers\PageBuilder;

class Dashboard
{

    public function __construct() {}

    public function index()
    {

        $components = [
            'dashboard-sidebar.php',
        ];

        switch ($_SERVER['REQUEST_URI']) {
            case '/dashboard/page-builder':
                $components[] = 'dashboard-page-builder.php';
                $data = new PageBuilder();
                $data->pageList();
                break;
            case '/dashboard/template':
                $components[] = 'dashboard-template.php';
                break;
            case '/dashboard/comment':
                $components[] = 'dashboard-comment.php';
                break;
            case '/dashboard/user':
                $components[] = 'dashboard-user-data.php';
                break;
            default:
                $components[] = 'dashboard-page-builder.php';
                break;
        }
        session_start();  
        if (!isset($_SESSION['user'])) {
            header('Location: /installer/login');
            exit;
        }
        else {
            include __DIR__ . '/../Views/back-office/dashboard/index.php';
        }
    }
}