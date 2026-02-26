<?php
namespace App\Models;

use App\Models\Enums\StatutJoueur;

final class Joueur
{
    public ?int $id = null;                 
    public string $nom;                     
    public string $prenom;                  
    public \DateTimeImmutable $dateNaissance; 
    public string $numeroLicence;           
    public int $taille;                    
    public int $poids;                     
    public StatutJoueur $statut;            

    public function __construct (
        ?int $id,
        string $nom,
        string $prenom,
        \DateTimeImmutable $dateNaissance,
        string $numeroLicence,
        int $taille,
        int $poids,
        StatutJoueur $statut
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaissance = $dateNaissance;
        $this->numeroLicence = $numeroLicence;
        $this->taille = $taille;
        $this->poids = $poids;
        $this->statut = $statut;
    }

    public static function fromArray(array $row): self {
        return new self(
            $row['IdJoueur'] ?? null,
            $row['Nom'],
            $row['Prenom'],
            new \DateTimeImmutable($row['DateNaissance']),
            $row['NumeroLicence'],
            (int)$row['Taille'],
            (int)$row['Poids'],
            StatutJoueur::from($row['Statut'])
        );
    }

    public function toArray(): array {
        return [
            'IdJoueur' => $this->id,
            'Nom' => $this->nom,
            'Prenom' => $this->prenom,
            'DateNaissance' => $this->dateNaissance->format('Y-m-d'),
            'NumeroLicence' => $this->numeroLicence,
            'Taille' => $this->taille,
            'Poids' => $this->poids,
            'Statut' => $this->statut->value,
        ];
    }

    /**
     * Calcule l'âge du joueur en années.
     */
    public function age(): int
    {
        $now = new \DateTimeImmutable();
        return $now->diff($this->dateNaissance)->y;
    }
}
