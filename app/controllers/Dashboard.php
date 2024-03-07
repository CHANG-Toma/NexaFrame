<?php

namespace App\Controllers;

use App\Controllers\PageBuilder;

class Dashboard
{

    public function __construct() {}

    public function index()
    {
        session_start(); 

        $components = [
            'dashboard-sidebar.php',
        ];

        switch ($_SERVER['REQUEST_URI']) {
            case '/dashboard/page-builder':
                $components[] = 'dashboard-page-builder.php';
                $pageBuilder = new PageBuilder();
                $data = $pageBuilder->pageList();
                break;
            case '/dashboard/page-builder/create-page':
                $components[] = 'dashboard-page.php';
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
        if (!isset($_SESSION['user'])) {
            header('Location: /installer/login');
            exit;
        }
        else {
            include __DIR__ . '/../Views/back-office/dashboard/index.php';
        }
    }
}