<?php
namespace App\Controllers;

use App\DAO\JoueurDao;
use PDO;
use Exception;

class JoueurController {
    private Joueurdao $dao;
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        // 1. Initialisation de la connexion PDO
        try {
            $host = $_ENV['DB_HOST'];
            $db = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];

            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            // 2. Injection de la connexion dans le DAO
            $this->dao = new JoueurDao($this->pdo);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Erreur de connexion à la base de données."]);
            exit;
        }
    }

    public function getAll() {
        // Récupération du tableau des Joueurs depuis le DAO
        $joueurs = $this->dao->all();
            
        // Transformation des objets en tableau
        $joueursArray = array_map(function($joueur) {
            return $joueur->toArray();
        }, $joueurs);

        http_response_code(200);
        echo json_encode($joueursArray);
        exit;
    }
}

?>