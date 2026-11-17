<?php

$path = parse_url(
    $_SERVER["REQUEST_URI"] ?? "/",
    PHP_URL_PATH
);

if (!is_string($path)) {
    http_response_code(400);
    exit("Chemin invalide.");
}

$publicDirectory = realpath(__DIR__ . "/public");
$file = realpath(__DIR__ . rawurldecode($path));

if (
    $publicDirectory !== false
    && $file !== false
    && str_starts_with(
        $file,
        $publicDirectory . DIRECTORY_SEPARATOR
    )
    && is_file($file)
) {
    return false;
}

require __DIR__ . "/index.php";
