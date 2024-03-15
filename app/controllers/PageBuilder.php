<?php

namespace App\Controllers;

use App\Models\Page;

class PageBuilder
{

    public function __construct()
    {
    }

    public function pageList(): array
    {
        $page = new Page();
        return $page->getAllBy(['id_creator' => $_SESSION['user']['id']]);
    }

    public function savePage(): void
    {
        session_start();

        $url = $_POST["url"] ?? '';
        $title = $_POST["title"] ?? '';
        $html = $_POST["html"] ?? '';
        $css = $_POST["css"] ?? '';
        $meta_description = $_POST["meta_description"] ?? '';

        if (!empty($url) && !empty($title) && !empty($html) && !empty($css) && !empty($meta_description)) {
            $Page = new Page();

            // update page 
            if (!empty($_POST["id"])) {
                $id = $_POST["id"];
                $Page->setId($id);
                $Page->setUpdatedAt(date('Y-m-d H:i:s'));
            }

            $Page->setUrl('/' . htmlspecialchars($url));
            $Page->setTitle(htmlspecialchars($title));
            $Page->setHtml($html);
            $Page->setCss($css);
            $Page->setIdCreator($_SESSION['user']['id']);
            $Page->setMetaDescription($meta_description);
            $Page->save();

            header('Content-Type: application/json');
            echo json_encode(["success" => true, "message" => "Page saved successfully"]);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
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