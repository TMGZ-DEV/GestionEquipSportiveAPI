<?php
namespace App\Controllers;

use Firebase\JWT\JWT;
use PDO;

class AuthController{
    private $db;

    public function __construct() {
        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $this->db = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

    }

    public function login() {
        header('Content-Type: application/json');

        // Récupération des données POST
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->email) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(["message" => "Email et mot de passe requis"]);
            return;
        }

        // 1. Recherche de l'utilisateur
        $stmt = $this->db->prepare("SELECT * FROM Utilisateur WHERE Email = :email");
        $stmt->execute(['email' => $data->email]);
        $user = $stmt->fetch();

        // 2. Vérification du mot de passe
        if ($user && password_verify($data->password, $user['MotDePasseHash'])) {
            
            // 3. Création du Token JWT
            $secret_key = "";
            $issuer_claim = $_ENV['DB_HOST'];
            $issuedat_claim = time();
            $expire_claim = $issuedat_claim + 3600;

            $token = array(
                "iss" => $issuer_claim,
                "iat" => $issuedat_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "id" => $user['IdUtilisateur'],
                    "role" => $user['Role']
                )
            );

            $jwt = JWT::encode($token, $secret_key, 'HS256');

            http_response_code(200);
            echo json_encode(
                array(
                    "message" => "Connexion réussie.",
                    "token" => $jwt
                )
            );
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Identifiants invalides."]);
        }
    } 
}

?>