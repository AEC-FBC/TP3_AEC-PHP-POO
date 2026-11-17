<?php

namespace App\Routes;

use App\Models\VisitLog;
use App\Providers\View;

class Route
{
    private static array $routes = [];

    public static function get(
        string $url,
        string $controller,
        string $role = "public"
    ): void {
        self::register($url, $controller, "GET", $role);
    }

    public static function post(
        string $url,
        string $controller,
        string $role = "public"
    ): void {
        self::register($url, $controller, "POST", $role);
    }

    private static function register(
        string $url,
        string $controller,
        string $method,
        string $role
    ): void {
        self::$routes[] = [
            "url" => $url,
            "controller" => $controller,
            "method" => $method,
            "role" => $role,
        ];
    }

    private static function authorize(string $role): bool
    {
        if ($role === "public") {
            return true;
        }

        $userRole = $_SESSION["user"]["role"] ?? null;

        if ($userRole === null) {
            View::redirect("login");
        }

        if ($role === "auth") {
            return true;
        }

        if ($role === "admin" && $userRole !== "admin") {
            http_response_code(403);

            View::render("error", [
                "msg" => "Vous n'avez pas accès à cette page.",
            ]);

            return false;
        }

        if (
            $role === "client" &&
            !in_array($userRole, ["client", "admin"], true)
        ) {
            http_response_code(403);

            View::render("error", [
                "msg" => "Vous n'avez pas accès à cette page.",
            ]);

            return false;
        }

        return true;
    }

    public static function dispatch(): void
    {
        $path = parse_url(
            $_SERVER["REQUEST_URI"] ?? "/",
            PHP_URL_PATH
        );

        $method = $_SERVER["REQUEST_METHOD"] ?? "";
        $allowedMethods = [];
        $user = $_SESSION["user"] ?? null;

        $username = $user["name"] ?? "Visiteur";

        (new VisitLog())->insert([
            "ip_address" => $_SERVER["REMOTE_ADDR"] ?? "0.0.0.0",
            "username" => $username,
            "page_url" => $_SERVER["REQUEST_URI"] ?? "/",
        ]);

        if (!is_string($path)) {
            http_response_code(400);

            View::render("error", [
                "msg" => "Adresse de requête invalide.",
            ]);

            return;
        }

        foreach (self::$routes as $route) {
            if (BASE . $route["url"] !== $path) {
                continue;
            }

            $allowedMethods[] = $route["method"];

            if ($route["method"] !== $method) {
                continue;
            }

            if (!self::authorize($route["role"])) {
                return;
            }

            [$controller, $action] = explode(
                "@",
                $route["controller"],
                2
            );

            $className = 'App\\Controllers\\' . $controller;
            $instance = new $className();

            if ($method === "POST") {
                $instance->$action($_POST, $_GET);
            } else {
                $instance->$action($_GET);
            }

            return;
        }

        if ($allowedMethods !== []) {
            header(
                "Allow: " . implode(", ", array_unique($allowedMethods))
            );

            http_response_code(405);

            View::render("error", [
                "msg" => "Cette méthode HTTP n'est pas autorisée.",
            ]);

            return;
        }

        http_response_code(404);

        View::render("error", [
            "msg" => "La page demandée est introuvable.",
        ]);
    }
}
