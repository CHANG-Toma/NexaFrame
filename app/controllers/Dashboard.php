<?php

namespace App\Controllers;

use App\Controllers\PageBuilder;
use App\Controllers\Error;
use App\Controllers\Article;
use App\Models\Category;

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
        $article = new Article();
        $Category = new Category();

        switch ($_SERVER['REQUEST_URI']) {
            case '/dashboard/page-builder':
                $components[] = 'dashboard-page-builder.php';
                $data = $pageBuilder->pageList();
                break;
            case '/dashboard/page-builder/create-page':
                $components[] = 'dashboard-page.php';
                break;
            case '/dashboard/create-article':
                $components[] = 'dashboard-create-article.php';
                $data = $Category->getAll();
                break;
            case '/dashboard/article':
                $components[] = 'dashboard-article-management.php';
                $data = $article->articleList();
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