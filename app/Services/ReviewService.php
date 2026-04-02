<?php
namespace App\Services;

use PDO;

class ReviewService
{
    public function __construct(private PDO $pdo)
    {
    }

    public function create(int $userId, string $title, string $content, int $note): bool
    {
        return $this->createAndReturnId($userId, $title, $content, $note) !== null;
    }

    public function createAndReturnId(int $userId, string $title, string $content, int $note): ?int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO critique (titre, contenu, note, date_creation, id_user) VALUES (?, ?, ?, NOW(), ?)'
        );

        if (!$statement->execute([$title, $content, $note, $userId])) {
            return null;
        }

        return (int) $this->pdo->lastInsertId();
    }

    public function getByUserIdWithLikes(int $userId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, titre, contenu, note, date_creation,
                    (SELECT COUNT(*) FROM likes WHERE id_critique = critique.id) AS likes_count,
                    EXISTS(SELECT 1 FROM likes WHERE id_critique = critique.id AND id_user = ?) AS user_liked
             FROM critique
             WHERE id_user = ?
             ORDER BY date_creation DESC, id DESC'
        );
        $statement->execute([$userId, $userId]);

        return $statement->fetchAll();
    }

    public function getAllWithLikesForViewer(int $viewerId): array
    {
        $statement = $this->pdo->query(
            'SELECT critique.id, critique.titre, critique.contenu, critique.note, critique.date_creation,
                    critique.id_user, user.pseudo, user.email,
                    (SELECT COUNT(*) FROM likes WHERE id_critique = critique.id) AS likes_count,
                    EXISTS(SELECT 1 FROM likes WHERE id_critique = critique.id AND id_user = ' . (int) $viewerId . ') AS user_liked
             FROM critique
             LEFT JOIN user ON user.id = critique.id_user
             ORDER BY critique.date_creation DESC, critique.id DESC'
        );

        return $statement->fetchAll();
    }

    public function findPublicByIdWithLikes(int $reviewId, ?int $viewerId = null): ?array
    {
        $viewerId = $viewerId ?? 0;

        $statement = $this->pdo->prepare(
            'SELECT critique.id, critique.titre, critique.contenu, critique.note, critique.date_creation,
                    critique.id_user, user.pseudo, user.email,
                    (SELECT COUNT(*) FROM likes WHERE id_critique = critique.id) AS likes_count,
                    EXISTS(SELECT 1 FROM likes WHERE id_critique = critique.id AND id_user = ?) AS user_liked
             FROM critique
             LEFT JOIN user ON user.id = critique.id_user
             WHERE critique.id = ?
             LIMIT 1'
        );
        $statement->execute([$viewerId, $reviewId]);
        $review = $statement->fetch();

        return $review ?: null;
    }

    public function findByIdForUser(int $reviewId, int $userId, bool $isAdmin = false): ?array
    {
        $query = $isAdmin
            ? 'SELECT * FROM critique WHERE id = ?'
            : 'SELECT * FROM critique WHERE id = ? AND id_user = ?';

        $statement = $this->pdo->prepare($query);
        $statement->execute($isAdmin ? [$reviewId] : [$reviewId, $userId]);
        $review = $statement->fetch();

        return $review ?: null;
    }

    public function update(int $reviewId, int $userId, bool $isAdmin, string $title, string $content, int $note): bool
    {
        $query = $isAdmin
            ? 'UPDATE critique SET titre = ?, contenu = ?, note = ? WHERE id = ?'
            : 'UPDATE critique SET titre = ?, contenu = ?, note = ? WHERE id = ? AND id_user = ?';

        $statement = $this->pdo->prepare($query);

        return $isAdmin
            ? $statement->execute([$title, $content, $note, $reviewId])
            : $statement->execute([$title, $content, $note, $reviewId, $userId]);
    }

    public function delete(int $reviewId, int $userId, bool $isAdmin): bool
    {
        $query = $isAdmin
            ? 'DELETE FROM critique WHERE id = ?'
            : 'DELETE FROM critique WHERE id = ? AND id_user = ?';

        $statement = $this->pdo->prepare($query);

        return $isAdmin
            ? $statement->execute([$reviewId])
            : $statement->execute([$reviewId, $userId]);
    }
}
