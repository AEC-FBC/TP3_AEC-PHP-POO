<?php
session_start();

use App\Providers\View;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";

try {
    require __DIR__ . "/routes/web.php";
} catch (Throwable $exception) {
    error_log((string) $exception);

    http_response_code(500);

    View::render("error", [
        "pageTitle" => "Erreur interne",
        "msg" => "Une erreur est survenue. Réessaie ou consulte les journaux du serveur.",
    ]);
}
