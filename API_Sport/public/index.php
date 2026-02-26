<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Middleware\AuthMiddleware;
use App\Controllers\JoueurController;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Headers CORS


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Verrification du TOKEN
$userData = AuthMiddleware::verifierToken();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Routage
if ($uri === '/joueurs' && $method === 'GET') {
    $controller = new JoueurController();
    $controller->getAll();
} else {
    http_response_code(404);
    echo json_encode(["message" => "Ressource non trouvée."]);
}



?>