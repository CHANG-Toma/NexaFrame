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
        $page  = new Page();
        return $page->getAllBy(['id_creator' => $_SESSION['user']['id']]);;
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

    public function deletePage(): void
    {
        $id = $_POST["id-page"];
        $Page = new Page();
        $Page->delete($id);
        if($Page->getOneBy(["id" => $id])){
            $_SESSION['error'] = "La page n'a pas été supprimée";
        } else {
            $_SESSION['success'] = "La page a été supprimée";
        }
        header('Location: /dashboard/page-builder');
    }
}