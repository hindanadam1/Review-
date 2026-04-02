<?php
session_start();
require_once '../config/db.php';

if ($authService->isLoggedIn()) {
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
        $user = $authService->login($login, $password);

        if ($user !== null) {
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
<body class="auth-bg" style="display:flex;flex-direction:column;align-items:stretch;justify-content:flex-start;">
    <main style="flex:1;width:100%;display:flex;align-items:center;justify-content:center;padding:32px 16px;">
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
    </main>
    <?php require_once '../includes/footer.php'; ?>
</body>
</html>

