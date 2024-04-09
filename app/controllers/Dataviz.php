<?php

namespace App\Controllers;

use App\Models\Comment;

class Dataviz
{

    // Récupère les données pour les graphiques et les stocke dans un fichier JSON
    public function fetchData() : void
    {
        $commentModel = new Comment();
        $comments = $commentModel->getAll();
        
        // On compte le nombre de commentaires par date
        $data = [];
        foreach ($comments as $comment) {
            $date = date('Y-m-d', strtotime($comment['created_at']));
            if (!isset($data[$date])) {
                $data[$date] = 1;
            } else {
                $data[$date]++;
            }
        }

        // On transforme le tableau en JSON
        $json = [];
        foreach ($data as $date => $count) {
            $json[] = [
                'date' => $date,
                'totalComments' => $count
            ];
        }

        file_put_contents("../app/Views/back-office/dashboard/data-amchart/data.json", json_encode($json));
    }
}