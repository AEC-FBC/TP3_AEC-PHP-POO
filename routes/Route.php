<?php

namespace App\Routes;

use App\Providers\View;

class Route
{
    private static array $routes = [];

    public static function get(
        string $url,
        string $controller
    ): void {
        self::$routes[] = [
            "url" => $url,
            "controller" => $controller,
            "method" => "GET",
        ];
    }

    public static function post(
        string $url,
        string $controller
    ): void {
        self::$routes[] = [
            "url" => $url,
            "controller" => $controller,
            "method" => "POST",
        ];
    }

    public static function dispatch(): void
    {
        $path = parse_url(
            $_SERVER["REQUEST_URI"] ?? "/",
            PHP_URL_PATH
        );

        $method = $_SERVER["REQUEST_METHOD"] ?? "";
        $allowedMethods = [];

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
