<?php

namespace App\Controllers;

use App\Models\Article as ArticleModel;

class Article
{

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset ($_POST['title']) && isset ($_POST['content']) && isset ($_POST['keywords']) && isset ($_POST['picture_url']) && isset ($_POST['category'])) {
                if(!isset($_SESSION)) { session_start(); }

                $article = new ArticleModel();
                $article->setTitle($_POST['title']);
                $article->setContent($_POST['content']);
                $article->setKeywords($_POST['keywords']);
                $article->setPictureUrl($_POST['picture_url']);
                $article->setCategoryId($_POST['category']);
                $article->setCreatorId($_SESSION['user']['id']);
                
                $article->save();

                header('Location: /dashboard/article');
            }
        }
    }

    public function articleList()
    {
        $article = new ArticleModel();
        return $article->getAllby(['id_creator' => $_SESSION['user']['id']]);
    }
}
