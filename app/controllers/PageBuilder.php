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
        $page = new Page();
        return $page->getAllBy(['id_creator' => $_SESSION['user']['id']]);
        ;
    }

    public function savePage(): void
    {
        session_start();

        if (isset($_POST["url"], $_POST["title"], $_POST["html"], $_POST["css"], $_POST["meta_description"])) {

            $url = $_POST["url"];
            $title = $_POST["title"];
            $html = $_POST["html"];
            $css = $_POST["css"];
            $meta_description = $_POST["meta_description"];

            // Use your Page model to save the data
            $Page = new Page();
            $Page->setUrl('/' . $url);
            $Page->setTitle(htmlspecialchars($title));
            $Page->setHtml($html);
            $Page->setCss($css);
            $Page->setIdCreator($_SESSION['user']['id']);
            $Page->setMetaDescription($meta_description);
            $Page->save();

            // Send a JSON response back
            header('Content-Type: application/json');
            echo json_encode(["success" => true, "message" => "Page saved successfully"]);
        } else {
            // Send a JSON response back
            header('Content-Type: application/json');
            http_response_code(400); // Bad request
            echo json_encode(["success" => false, "message" => "Missing required fields"]);
        }
    }



    public function deletePage(): void
    {
        $id = $_POST["id-page"];
        $Page = new Page();
        $Page->delete($id);
        session_start();
        if (!empty($Page->getOneBy(["id" => $id]))) {
            $_SESSION['error_message'] = "La page n'a pas été supprimée";
        } else {
            $_SESSION['success_message'] = "La page a été supprimée";
        }
        header('Location: /dashboard/page-builder');
    }
}