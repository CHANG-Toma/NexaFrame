<?php

namespace App\Controllers;

use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;
use App\Models\Comment as CommentModel;

class Article
{

    public function save(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['title'], $_POST['content'], $_POST['keywords'], $_POST['picture_url'], $_POST['category'])) {
                if (!isset($_SESSION)) {
                    session_start();
                }

                $article = new ArticleModel();

                if (isset($_POST['id-article']) && $_POST['id-article'] !== '') {
                    $article->setId((int) $_POST['id-article']);
                    $article->setUpdatedAt(date('Y-m-d H:i:s'));
                }

                if(isset($_POST['published_at']) && $_POST['published_at'] !== '') {
                    $article->setPublishedAt(date('Y-m-d H:i:s'));
                }
                else{
                    $article->setPublishedAt(null);
                }

                $article->setTitle(htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'));
                $article->setContent(htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8'));
                $article->setKeywords(htmlspecialchars($_POST['keywords'], ENT_QUOTES, 'UTF-8'));
                $article->setPictureUrl(htmlspecialchars($_POST['picture_url'], ENT_QUOTES, 'UTF-8'));
                $article->setCategoryId((int) $_POST['category']);
                $article->setCreatorId((int) $_SESSION['user']['id']);

                $article->save();

                header('Location: /dashboard/article');
            }
        }
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['id-article'])) {
                $article = new ArticleModel();
                $article->delete($_POST['id-article']);

                if (!isset($_SESSION)) {
                    session_start();
                }

                if (!empty($article->getOneBy(['id' => $_POST['id-article']]))) {
                    $_SESSION['error_message'] = "L'article n'a pas pu être supprimé";
                } else {
                    $_SESSION['success_message'] = "L'article a bien été supprimé";
                }

                header('Location: /dashboard/article');
            }
        }
    }

    public function showAll() : array
    {
        if (!isset($_SESSION)) {
            session_start();
        }

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

    public function getArticlesJson(): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $article = new ArticleModel();
        $articles = $article->getAllby(['id_creator' => $_SESSION['user']['id'], 'published_at' => ['operator' => 'IS NOT NULL']]);

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

            $commentModel = new CommentModel();
            $comments = $commentModel->getAll();

            foreach ($comments as $comment) {
                if ($comment['id_article'] == $article['id'] && $comment['valid'] == 1) {
                    $article['comments'][] = $comment;
                }
            }
            $articleList[] = $article;
        }

        echo json_encode($articleList);
    }
}
