<?php

namespace App\Controllers;

use App\Models\Comment as CommentModel;
use App\Models\User;
use App\Models\Article;

class Comment
{

    public function save()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION["user"])) {
            $comment = $_POST["commentContent"];

            $commentModel = new CommentModel();
            $commentModel->setArticleId($_POST["articleId"]);
            $commentModel->setContent($comment);
            $commentModel->setUserId($_SESSION["user"]["id"]);
            $commentModel->setValid(0);
            $commentModel->save();

            header("Location: /home");
        }

    }

    public function showAll(): array
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $comment = new CommentModel();
        $user = new User();
        $article = new Article();

        $comments = $comment->getAll();
        $commentList = [];
        
        foreach ($comments as $comment) {

            $login = $user->getOneBy(["id" => $comment["id_user"]]);
            $articleData = $article->getOneBy(["id" => $comment["id_article"]]);

            foreach ($login as $log) {
                $comment["author"] = $log["login"];

                foreach ($articleData as $art) {
                    $comment["articleTitle"] = $art["title"];
                }

                $commentList[] = $comment;
            }


        }

        return $commentList;
    }

    public function delete($id)
    {
        // Code to delete a specific comment by ID
    }

    public function approve($id)
    {
        // Code to approve a specific comment by ID
    }
}
