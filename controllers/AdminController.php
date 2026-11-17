<?php

namespace App\Controllers;

use App\Providers\View;

class AdminController
{
    public function index(array $query = []): void
    {
        // Vérifier que l'utilisateur est administrateur.
        if (
            !isset($_SESSION["user"]) ||
            $_SESSION["user"]["role"] !== "admin"
        ) {
            View::redirect("login");
        }

        View::render("admin/index", [
            "pageTitle" => "Administration",
            "user" => $_SESSION["user"],
        ]);
    }
}
