<?php

namespace App\Controllers;

class Error
{
    // Affiche une page d'erreur 404
    public function error404(): void
    {
        ob_start();
        http_response_code(404);
        //include une page error404
        ob_end_flush();
    }

    // Affiche une page d'erreur 403
    public function error403(): void
    {
        ob_start();
        http_response_code(403);
        ob_end_flush();
    }
}