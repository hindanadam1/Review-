<?php
namespace App\Services;

use PDO;

class UserService
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getAllWithReviewCounts(): array
    {
        $statement = $this->pdo->query(
            'SELECT user.id, user.pseudo, user.email, user.role,
                    COUNT(critique.id) AS total_reviews
             FROM user
             LEFT JOIN critique ON critique.id_user = user.id
             GROUP BY user.id, user.pseudo, user.email, user.role
             ORDER BY user.role DESC, user.id DESC'
        );

        return $statement->fetchAll();
    }

    public function deleteById(int $userId): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM user WHERE id = ?');
        return $statement->execute([$userId]);
    }
}
