<?php
namespace App\Services;

use PDO;

class CategoryService
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getAll(): array
    {
        $statement = $this->pdo->query(
            'SELECT id, nom
             FROM categorie
             ORDER BY nom ASC'
        );

        return $statement->fetchAll();
    }

    public function attachToReview(int $reviewId, int $categoryId): bool
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO critique_categorie (id_critique, id_categorie) VALUES (?, ?)'
        );

        return $statement->execute([$reviewId, $categoryId]);
    }

    public function getByReviewId(int $reviewId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT categorie.id, categorie.nom
             FROM critique_categorie
             INNER JOIN categorie ON categorie.id = critique_categorie.id_categorie
             WHERE critique_categorie.id_critique = ?
             ORDER BY categorie.nom ASC'
        );
        $statement->execute([$reviewId]);

        return $statement->fetchAll();
    }
}
