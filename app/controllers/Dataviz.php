<?php

namespace App\Controllers;

use App\Models\Comment;

class Dataviz
{

    public function fetchData()
    {

        $commentModel = new Comment();
        $comments = $commentModel->getAll();
        
        $data = [];
        foreach ($comments as $comment) {
            $date = date('Y-m-d', strtotime($comment['created_at']));
            if (!isset($data[$date])) {
                $data[$date] = 1;
            } else {
                $data[$date]++;
            }
        }

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