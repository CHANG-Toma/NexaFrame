<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Page;
use App\Models\Setting;

class PageBuilder
{

    public function __construct()
    {
    }

    public function pageList(): array
    {
        
        if (!isset($_SESSION['user']['id'])) {
            return [];
        }

        $userId = $_SESSION['user']['id'];
        $conditions = ['id_creator' => $userId];

        $Page = new Page();

        return $Page->getAllBy($conditions);
    }

    public function savePage($route): void
    {
        $id = $_POST["id"];
        $url = $_POST["url"];
        $title = $_POST["title"];
        $content = $_POST["content"];

        $Page = new Page();
        $dataPage = $Page->getOneBy(["id" => $id]);
        $Page->populate($dataPage);
        $Page->setUrl('/' . $url);
        $Page->setTitle(htmlspecialchars($title));
        $Page->setContent($content);
        $Page->save();

    }

    public function displayPage(int $idPage): void
    {

    }

    public function lastArticles($route): void
    {

    }
}