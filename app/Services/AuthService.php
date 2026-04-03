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
        // Retourne toutes les informations de l'utilisateur stockees dans la session.
        return $_SESSION['user'] ?? null;
    }

    public function userId(): ?int
    {
        // Retourne uniquement l'id de l'utilisateur connecte.
        return isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null;
    }

    public function isLoggedIn(): bool
    {
        // Verifie si un utilisateur est connecte.
        return $this->userId() !== null;
    }

    public function isAdmin(): bool
    {
        // Verifie si l'utilisateur connecte a le role administrateur.
        return isset($_SESSION['user']['role']) && (int) $_SESSION['user']['role'] === 2;
    }

    public function requireLogin(string $redirect = '../auth/login.php'): void
    {
        // Bloque l'acces aux pages privees si aucune session utilisateur n'existe.
        if (!$this->isLoggedIn()) {
            header('Location: ' . $redirect);
            exit();
        }
    }

    public function requireAdmin(string $redirect = '../auth/login.php'): void
    {
        // Verifie d'abord la connexion, puis le role administrateur.
        $this->requireLogin($redirect);

        if (!$this->isAdmin()) {
            die('Acces reserve aux administrateurs.');
        }
    }

    public function login(string $login, string $password): ?array
    {
        // Recherche le compte par email ou pseudo.
        $statement = $this->pdo->prepare(
            'SELECT id, pseudo, email, password, role
             FROM user
             WHERE LOWER(email) = LOWER(?) OR pseudo = ?
             ORDER BY id DESC
             LIMIT 1'
        );
        $statement->execute([$login, $login]);
        $user = $statement->fetch();

        // Verifie que le compte existe et que le mot de passe est correct.
        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }

        // Regenerer l'id de session limite les risques de session fixation.
        session_regenerate_id(true);

        // Stocke l'utilisateur connecte dans la session pour tout le site.
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
        // Verifie qu'aucun compte avec le meme email ou pseudo n'existe deja.
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

        // Hash le mot de passe avant de l'enregistrer en base.
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
        // Supprime les donnees de session puis detruit la session.
        $_SESSION = [];
        session_destroy();
        header('Location: ' . $redirect);
        exit();
    }
}
