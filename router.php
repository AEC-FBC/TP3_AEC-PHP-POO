<?php

$path = parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH);

if (!is_string($path)) {
    http_response_code(400);
    exit("Chemin invalide.");
}

$path = rawurldecode($path);
$publicDirectory = realpath(__DIR__ . "/public");
function getMimeType(string $file): string
{
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    return match ($extension) {
        "css" => "text/css",
        "js" => "application/javascript",
        "svg" => "image/svg+xml",
        "jpg", "jpeg" => "image/jpeg",
        "png" => "image/png",
        "webp" => "image/webp",
        default => mime_content_type($file) ?: "application/octet-stream",
    };
}
if ($publicDirectory !== false) {
    $file = realpath($publicDirectory . $path);

    if (
        $file !== false
        && str_starts_with($file, $publicDirectory . DIRECTORY_SEPARATOR)
        && is_file($file)
        && is_readable($file)
    ) {
        header("Content-Type: " . getMimeType($file));
        readfile($file);
        exit;
    }
}

if (str_starts_with($path, "/public/")) {
    $file = realpath(__DIR__ . $path);

    if (
        $file !== false
        && $publicDirectory !== false
        && str_starts_with($file, $publicDirectory . DIRECTORY_SEPARATOR)
        && is_file($file)
        && is_readable($file)
    ) {
        header("Content-Type: " . getMimeType($file));
        readfile($file);
        exit;
    }
}
require __DIR__ . "/index.php";
