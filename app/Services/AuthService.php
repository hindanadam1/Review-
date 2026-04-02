<?php
namespace App\Services;

use PDO;

class AuthService
{
    public function __construct(private PDO $pdo)
    {
    }

    public function currentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public function userId(): ?int
    {
        return isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null;
    }

    public function isLoggedIn(): bool
    {
        return $this->userId() !== null;
    }

    public function isAdmin(): bool
    {
        return isset($_SESSION['user']['role']) && (int) $_SESSION['user']['role'] === 2;
    }

    public function requireLogin(string $redirect = '../auth/login.php'): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . $redirect);
            exit();
        }
    }

    public function requireAdmin(string $redirect = '../auth/login.php'): void
    {
        $this->requireLogin($redirect);

        if (!$this->isAdmin()) {
            die('Acces reserve aux administrateurs.');
        }
    }

    public function login(string $login, string $password): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, pseudo, email, password, role
             FROM user
             WHERE LOWER(email) = LOWER(?) OR pseudo = ?
             ORDER BY id DESC
             LIMIT 1'
        );
        $statement->execute([$login, $login]);
        $user = $statement->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'pseudo' => $user['pseudo'],
            'email' => $user['email'],
            'role' => (int) $user['role'],
        ];

        return $_SESSION['user'];
    }

    public function register(string $pseudo, string $email, string $plainPassword, int $role = 1): array
    {
        $checkStatement = $this->pdo->prepare(
            'SELECT id FROM user WHERE LOWER(email) = LOWER(?) OR pseudo = ? LIMIT 1'
        );
        $checkStatement->execute([$email, $pseudo]);

        if ($checkStatement->fetch()) {
            return [
                'success' => false,
                'message' => 'Un compte avec cet email ou ce pseudo existe deja.',
            ];
        }

        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        $statement = $this->pdo->prepare(
            'INSERT INTO user (pseudo, email, password, role) VALUES (?, ?, ?, ?)'
        );
        $statement->execute([$pseudo, $email, $hashedPassword, $role]);

        return [
            'success' => true,
            'message' => 'Compte cree !',
        ];
    }

    public function logout(string $redirect = 'login.php'): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . $redirect);
        exit();
    }
}
