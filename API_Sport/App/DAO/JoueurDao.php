<?php
namespace App\DAO;

use App\Models\Joueur;
use App\Models\Enums\StatutJoueur;
use PDO;

final class JoueurDao
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM Joueur ORDER BY Nom, Prenom');
        $joueurs = [];
        while ($row = $stmt->fetch()) {
            $joueurs[] = Joueur::fromArray($row);
        }
        return $joueurs;
    }

    public function findById(int $id): ?Joueur
    {
        $stmt = $this->pdo->prepare('SELECT * FROM Joueur WHERE IdJoueur = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? Joueur::fromArray($row) : null;
    }

    public function findActifs(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM Joueur WHERE Statut = ? ORDER BY Nom, Prenom');
        $stmt->execute([StatutJoueur::ACTIF->value]);
        $joueurs = [];
        while ($row = $stmt->fetch()) {
            $joueurs[] = Joueur::fromArray($row);
        }
        return $joueurs;
    }

    public function create(Joueur $joueur): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO Joueur (Nom, Prenom, DateNaissance, NumeroLicence, Taille, Poids, Statut)
            VALUES (:nom, :prenom, :dateNaissance, :numeroLicence, :taille, :poids, :statut)
        ');
        
        $stmt->execute([
            'nom' => $joueur->nom,
            'prenom' => $joueur->prenom,
            'dateNaissance' => $joueur->dateNaissance->format('Y-m-d'),
            'numeroLicence' => $joueur->numeroLicence,
            'taille' => $joueur->taille,
            'poids' => $joueur->poids,
            'statut' => $joueur->statut->value
        ]);
        
        return (int)$this->pdo->lastInsertId();
    }

    public function update(Joueur $joueur): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE Joueur 
            SET Nom = :nom, 
                Prenom = :prenom, 
                DateNaissance = :dateNaissance, 
                NumeroLicence = :numeroLicence, 
                Taille = :taille, 
                Poids = :poids, 
                Statut = :statut
            WHERE IdJoueur = :id
        ');
        
        return $stmt->execute([
            'nom' => $joueur->nom,
            'prenom' => $joueur->prenom,
            'dateNaissance' => $joueur->dateNaissance->format('Y-m-d'),
            'numeroLicence' => $joueur->numeroLicence,
            'taille' => $joueur->taille,
            'poids' => $joueur->poids,
            'statut' => $joueur->statut->value,
            'id' => $joueur->id
        ]);
    }

    public function delete(int $id): bool
    {
        // Supprimer d'abord les participations
        $stmt = $this->pdo->prepare('DELETE FROM Participer WHERE IdJoueur = ?');
        $stmt->execute([$id]);
        
        // Puis les commentaires
        $stmt = $this->pdo->prepare('DELETE FROM Commentaire WHERE IdJoueur = ?');
        $stmt->execute([$id]);
        
        // Puis le joueur
        $stmt = $this->pdo->prepare('DELETE FROM Joueur WHERE IdJoueur = ?');
        return $stmt->execute([$id]);
    }

    public function exists(string $numeroLicence, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM Joueur WHERE NumeroLicence = ? AND IdJoueur != ?');
            $stmt->execute([$numeroLicence, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM Joueur WHERE NumeroLicence = ?');
            $stmt->execute([$numeroLicence]);
        }
        return $stmt->fetchColumn() > 0;
    }
}