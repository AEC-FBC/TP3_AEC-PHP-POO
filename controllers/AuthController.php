<?php

namespace App\Controllers;

use App\Providers\View;
use App\Models\User;

class AuthController
{

    public function login(array $query = []): void
    {
        $error = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim($_POST["email"] ?? "");
            $password = $_POST["password"] ?? "";

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user["password"])) {
                session_regenerate_id(true);

                $_SESSION["user"] = [
                    "id" => $user["id"],
                    "name" => $user["name"],
                    "email" => $user["email"],
                    "role" => $user["role"],
                ];

                View::redirect(
                    $user["role"] === "admin" ? "admin" : "projects"
                );
                exit;
            }

            $error = "Adresse courriel ou mot de passe incorrect.";
        }

        View::render("auth/login", [
            "pageTitle" => "Connexion",
            "error" => $error,
        ]);
    }



    public function logout(): void
    {
        // Supprimer les données de session
        $_SESSION = [];

        // Supprimer le cookie de session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(session_name(), "", [
                "expires" => time() - 42000,
                "path" => $params["path"],
                "domain" => $params["domain"],
                "secure" => $params["secure"],
                "httponly" => $params["httponly"],
                "samesite" => $params["samesite"] ?? "Lax",
            ]);
        }

        // Détruire la session
        session_destroy();

        // Retourner à la page de connexion
        View::redirect("login");
    }
}
