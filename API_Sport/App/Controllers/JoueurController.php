<?php
namespace App\Controllers;

use App\DAO\JoueurDao;
use PDO;
use Exception;

class JoueurController {
    private Joueurdao $dao;

    public function __construct(PDO $pdo) {
        $this->dao = new JoueurDao($pdo);
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