<?php

namespace App\Controllers;

use App\Controllers\PageBuilder;
use App\Controllers\Error;

class Dashboard
{

    public function __construct()
    {
    }

    public function index()
    {
        session_start();

        $components = [
            'dashboard-sidebar.php',
        ];
        $pageBuilder = new PageBuilder();

        switch ($_SERVER['REQUEST_URI']) {
            case '/dashboard/page-builder':
                $components[] = 'dashboard-page-builder.php';
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
                $data = $pageBuilder->pageList();
                break;
        }
        if ($_SESSION['user']['role'] != "admin") {
            $object = new Error();
            $object->error403();
        } else {
            include __DIR__ . '/../Views/back-office/dashboard/index.php';
        }

    }
}