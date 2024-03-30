<?php

namespace App\Controllers;

use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;

class Article
{

    public function save() : void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset ($_POST['title']) && isset ($_POST['content']) && isset ($_POST['keywords']) && isset ($_POST['picture_url']) && isset ($_POST['category'])) {
                if(!isset($_SESSION)) { session_start(); }

                $article = new ArticleModel();

                if(isset($_POST['id-article']) && $_POST['id-article'] != ''){
                    $article->setId($_POST['id-article']);
                }

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

    public function delete() : void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset ($_POST['id-article'])) {
                $article = new ArticleModel();
                $article->delete($_POST['id-article']);

                if(!isset($_SESSION)) { session_start(); }

                if(!empty($article->getOneBy(['id' => $_POST['id-article']]))){
                    $_SESSION['error_message'] = "L'article n'a pas pu être supprimé";
                }
                else{
                    $_SESSION['success_message'] = "L'article a bien été supprimé";
                }
            
                header('Location: /dashboard/article');
            }
        }
    }

    public function articleList()
    {
        if(!isset($_SESSION)) { session_start(); }

        $article = new ArticleModel();
        $articles = $article->getAllby(['id_creator' => $_SESSION['user']['id']]);

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAll();

        $articleList = [];
        foreach ($articles as $article) {
            $categoryId = $article['id_category'];
            $categoryName = '';
            foreach ($categories as $category) {
                if ($category['id'] == $categoryId) {
                    $categoryName = $category['label'];
                    break;
                }
            }

            $article['category'] = $categoryName;
            $articleList[] = $article;
        }

        return $articleList;
    }
}