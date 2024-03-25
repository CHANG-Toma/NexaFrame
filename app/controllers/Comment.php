<?php

namespace App\Controllers;

use App\Models\Comment as CommentModel;

class Comment
{

    public function create()
    {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!empty($_SESSION["user"])) {
        $comment = $_POST["comment"];

        $commentModel = new CommentModel();
        $commentModel->setContent($comment);
        $commentModel->setUserId($_SESSION["user"]["id"]);
        $commentModel->setValid(0);
        $commentModel->save();
        
        header("Location: /home");
    }
    
    }

    public function show($id)
    {
        // Code to fetch and display a specific comment by ID
    }

    public function update($id)
    {
        // Code to update a specific comment by ID
    }

    public function delete($id)
    {
        // Code to delete a specific comment by ID
    }
}
