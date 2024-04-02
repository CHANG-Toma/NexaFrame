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

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/dashboard/comment/delete') {
            if (isset($_POST['comment_id'])) {
                if (!isset($_SESSION)) {
                    session_start();

                }
                $comment = new CommentModel();
                $comment->delete($_POST['comment_id']);

                if (!$comment->getOneBy(["id" => $_POST['comment_id']])) {
                    $_SESSION["success_message"] = "Commentaire supprimé avec succès";
                } else {
                    $_SESSION["error_message"] = "Erreur lors de la suppression du commentaire";
                }
            } else {
                $_SESSION["error_message"] = "Données manquantes";
            }
        } else {
            $_SESSION["error_message"] = "Requete invalide";
        }
        header('Location: /dashboard/comment');
    }

    public function approve()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/dashboard/comment/approve') {
            if (isset($_POST['comment_id'])) {
                if (!isset($_SESSION)) {
                    session_start();
                }

                $comment = new CommentModel();

                $comment->setId($_POST['comment_id']);
                $comment->setValid(true);
                $comment->setValidateAt(date("Y-m-d H:i:s"));
                $comment->setValidatorId($_SESSION["user"]["id"]);
                $comment->save();
                
                $approved = $comment->getOneBy(["id" => $_POST['comment_id']]);

                if ($approved[0]["valid"] === true) {
                    $_SESSION["success_message"] = "Commentaire approuvé avec succès";
                } else {
                    $_SESSION["error_message"] = "Erreur lors de l'approbation du commentaire";
                }
            } else {
                $_SESSION["error_message"] = "Données manquantes";
            }
        } else {
            $_SESSION["error_message"] = "Requete invalide";
        }
        header('Location: /dashboard/comment');
    }
}
