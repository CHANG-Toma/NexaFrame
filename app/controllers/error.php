<?php

namespace App\Controllers;

class Error
{
    public function error404(): void
    {
        ob_start();
        http_response_code(404);
        //include une page error404
        ob_end_flush();
    }

    public function error403(): void
    {
        ob_start();
        http_response_code(403);
        ob_end_flush();
    }
}