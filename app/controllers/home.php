<?php

namespace App\Controllers;

use App\Core\DB;
use App\Models\Page;
use App\Controllers\Error;

class home
{
    public function __construct()
    {
        define('BASE_DIR', __DIR__ . '/..');
    }

    public function index(): void
    {
        if ($_SERVER["REQUEST_URI"] === "/") {
            include __DIR__ . '/../Views/front-office/main/home.php';
        } else {
            $Error = new Error();
            $Error->error404();
        }
    }

    public function mypage($uri = ''): bool
    {
        $Page = new Page();

        $Data = $Page->getAll();
        $pageExist = $Page->getOneBy(['url' => $uri]);

        if (!$Data || !$pageExist) {
            $Error = new Error();
            $Error->error404();
        }
        $pageData = null;

        foreach ($Data as $page) {
            if ($page['url'] === $uri) {
                $pageData = $page;
                break;
            }
        }

        if (!$pageData) {
            return false;
        } else {
            include __DIR__ . '/../Views/front-office/page/page.php';
            return true;
        }
    }

}
