<?php

namespace App\Controllers;

use App\Controllers\PageBuilder;
use App\Controllers\Error;
use App\Controllers\Article;
use App\Controllers\Comment;
use App\Controllers\User;
use App\Controllers\Dataviz;

use App\Models\Article as ArticleModel;
use App\Models\Category;

class Dashboard
{
    public function index()
    {
        session_start();

        $components = [
            'dashboard-sidebar.php',
        ];
        $pageBuilder = new PageBuilder();
        $article = new Article();
        $Category = new Category();
        $articleModel = new ArticleModel();
        $Comment = new Comment();
        $User = new User();
        $Dataviz = new Dataviz();

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
            case '/dashboard/update-article':
                $components[] = 'dashboard-create-article.php';
                $data = $Category->getAll();
                $dataArticle = $articleModel->getOneBy(['id' => $_POST['id-article']]);
                break;
            case '/dashboard/article':
                $components[] = 'dashboard-article-management.php';
                $data = $article->showAll();
                break;
                
            case '/dashboard/chart':
                $components[] = 'dashboard-chart.php';
                $Dataviz->fetchData();
                break;

            case '/dashboard/comment':
                $components[] = 'dashboard-comment.php';
                $comments = $Comment->showAll();
                break;
            case '/dashboard/user':
                $components[] = 'dashboard-user-data.php';
                break;
            case '/dashboard/list-users':
                $components[] = 'dashboard-list-users.php';
                $data = $User->showAll();
                break;
            default:
                $components[] = 'dashboard-page-builder.php';
                $data = $pageBuilder->pageList();
                break;
        }
        if ($_SESSION['user']['role'] == "admin" || $_SESSION['user']['role'] == 'superadmin') {
            include __DIR__ . '/../Views/back-office/dashboard/index.php';
        } else {
            $object = new Error();
            $object->error403();
        }

    }
}