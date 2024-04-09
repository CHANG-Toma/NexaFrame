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
    // Permet la gestion du back-office en fonction de l'URL
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
                $data = $pageBuilder->pageList(); // Récupère toutes les pages
                break;
            case '/dashboard/page-builder/create-page':
                $components[] = 'dashboard-page.php';
                break;
            case '/dashboard/create-article':
                $components[] = 'dashboard-create-article.php';
                $data = $Category->getAll(); // Récupère toutes les catégories
                break;
            case '/dashboard/update-article':
                $components[] = 'dashboard-create-article.php';
                $data = $Category->getAll(); // Récupère toutes les catégories
                $dataArticle = $articleModel->getOneBy(['id' => $_POST['id-article']]); // Récupère l'article à modifier
                break;
            case '/dashboard/article':
                $components[] = 'dashboard-article-management.php';
                $data = $article->showAll(); // Récupère tous les articles
                break;

            case '/dashboard/chart':
                $components[] = 'dashboard-chart.php';
                $Dataviz->fetchData(); // Récupère les données pour les graphiques
                break;

            case '/dashboard/comment':
                $components[] = 'dashboard-comment.php';
                $comments = $Comment->showAll(); // Récupère tous les commentaires
                break;
            case '/dashboard/user':
                $components[] = 'dashboard-user-data.php';
                break;
            case '/dashboard/list-users':
                $components[] = 'dashboard-list-users.php';
                $data = $User->showAll(); // Récupère tous les utilisateurs
                break;
            default:
                $components[] = 'dashboard-page-builder.php';
                $data = $pageBuilder->pageList(); // Récupère toutes les pages
                break;
        }
        // Si l'utilisateur est connecté, on affiche le back-office sinon on affiche une erreur 403
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == "admin" || $_SESSION['user']['role'] == 'superadmin') {
                include __DIR__ . '/../Views/back-office/dashboard/index.php';
            }
        } else {
            $object = new Error();
            $object->error403();
        }

    }
}