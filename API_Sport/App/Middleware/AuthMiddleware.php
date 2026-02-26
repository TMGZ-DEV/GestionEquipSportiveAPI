<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthMiddleware
{

    // Vérifie la présence et la validité du token dans les headers
    // Stoppe l'exécution si le token est invalide
    public static function verifierToken()
    {

        // Récupération des en-têtes HTTP
        $headers = apache_request_headers();

        // Recherche du header Authorization
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

        // Format attendu "Bearer <token>"
        if (!$authHeader || !preg_match('/Bearer\s(s\S+)/', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(["message" => "Accès refusé. Jeton d'authentification manquant ou mal formaté"]);
            exit;
        }

        // Extraction du token
        $jwt = $matches[1];

        // Récuperation de la clé secrète depuis le .env
        $secret_key = $_ENV['JWT_SECRET'];

        try {

            // Firebase verifie automatiquement la signature et la date d'expiration
            $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

            // Si le token est valide, on retourne les données de l'utilisateur
            return $decoded->data;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["message" => "Accès refusé. Jeton invalide ou expiré."]);
            exit;
        }
    }
}
?>