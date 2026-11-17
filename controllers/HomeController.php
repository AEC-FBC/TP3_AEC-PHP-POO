<?php

namespace App\Controllers;

use App\Providers\View;

class HomeController
{
    public function index(array $query = []): void
    {
        View::render("home", [
            "pageTitle" => "Accueil",
        ]);
    }
}
