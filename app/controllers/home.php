<?php

namespace App\Controllers;

use App\Core\DB;
use App\Models\Page;

Class home
{
    public function __construct()
    {
        define('BASE_DIR', __DIR__ . '/..');
    }

    public function index(): void
    {
        include __DIR__ . '/../Views/front-office/main/home.php';
    }

    public function mypage($uri = '') : bool
    {
        $Page = new Page();
        
        $Data = $Page->getAll();
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
