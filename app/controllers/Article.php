<?php

namespace App\Controllers;

use App\Models\Article as ArticleModel;

class Article
{

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset ($_POST['title']) && isset ($_POST['content']) && isset ($_POST['keywords']) && isset ($_POST['picture_url'])) {
                $title = $_POST['title'];
                $content = $_POST['content'];
                $keywords = $_POST['keywords'];
                $picture_url = $_POST['picture_url'];

                $article = new ArticleModel();
                $article->setTitle($title);
                $article->setContent($content);
                $article->setKeywords($keywords);
                $article->setPictureUrl($picture_url);
                $article->save();
            }
        }
    }

    public function articleList()
    {
        $article = new ArticleModel();
        return $article->getAllby(['id_creator' => $_SESSION['user']['id']]);
    }
}
