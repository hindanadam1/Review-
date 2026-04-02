<?php
namespace App\Services;

use PDO;

class LikeService
{
    public function __construct(private PDO $pdo)
    {
    }

    public function toggle(int $userId, int $critiqueId): array
    {
        $checkStatement = $this->pdo->prepare(
            'SELECT 1 FROM likes WHERE id_user = ? AND id_critique = ?'
        );
        $checkStatement->execute([$userId, $critiqueId]);

        if ($checkStatement->fetch()) {
            $statement = $this->pdo->prepare(
                'DELETE FROM likes WHERE id_user = ? AND id_critique = ?'
            );
            $statement->execute([$userId, $critiqueId]);
            $status = 'unliked';
        } else {
            $statement = $this->pdo->prepare(
                'INSERT INTO likes (id_user, id_critique) VALUES (?, ?)'
            );
            $statement->execute([$userId, $critiqueId]);
            $status = 'liked';
        }

        $countStatement = $this->pdo->prepare(
            'SELECT COUNT(*) AS total FROM likes WHERE id_critique = ?'
        );
        $countStatement->execute([$critiqueId]);
        $likes = (int) $countStatement->fetch()['total'];

        return [
            'status' => $status,
            'likes' => $likes,
        ];
    }
}
