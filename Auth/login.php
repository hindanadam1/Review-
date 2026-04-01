<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['user']['id'])) {
    header("Location: ../pages/dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $pdo->prepare("SELECT id, pseudo, email, password, role FROM user WHERE LOWER(email) = LOWER(?) OR pseudo = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id' => (int) $user['id'],
                'pseudo' => $user['pseudo'],
                'email' => $user['email'],
                'role' => (int) $user['role']
            ];

            header("Location: ../pages/dashboard.php");
            exit();
        }

        $error = "Email, pseudo ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Revieweo</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="auth-bg">
    <div class="auth-container">
        <div class="auth-animated-glow"></div>
        <div class="auth-content">
            <div class="auth-title">Se connecter</div>
            <?php if ($error): ?>
                <div class="auth-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <form class="auth-form" method="POST" autocomplete="off">
                <input name="email" type="text" placeholder="Email ou pseudo" required maxlength="100">
                <input name="password" type="password" placeholder="Mot de passe" required maxlength="64">
                <button type="submit">Connexion</button>
            </form>
            <div class="auth-footer-link">
                <a href="register.php">Pas encore de compte ? S'inscrire</a>
            </div>
        </div>
    </div>
</body>
</html>
