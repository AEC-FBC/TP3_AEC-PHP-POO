<?php

namespace App\Providers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    public static function render(
        string $template,
        array $data = []
    ): void {
        $loader = new FilesystemLoader(
            dirname(__DIR__) . "/views"
        );

        $twig = new Environment($loader, [
            "autoescape" => "html",
        ]);

        $twig->addGlobal("base", BASE);
        $twig->addGlobal("asset", ASSET);

        echo $twig->render($template . ".php", $data);
    }

    public static function redirect(string $path): void
    {
        header(
            "Location: " . BASE . "/" . ltrim($path, "/"),
            true,
            303
        );

        exit;
    }
}
