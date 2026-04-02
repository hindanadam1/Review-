<?php
session_start();
require_once '../config/db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $plainPassword = $_POST['password'] ?? '';

    if ($pseudo === '' || $email === '' || $plainPassword === '') {
        $error = "<div class='auth-error'>Veuillez remplir tous les champs.</div>";
    } else {
        $result = $authService->register($pseudo, $email, $plainPassword);

        if (!$result['success']) {
            $error = "<div class='auth-error'>" . htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8') . "</div>";
        } else {
            $message = "<div class='success-msg'>Compte cree ! <a href='/revieweo/auth/login.php'>Se connecter</a></div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | Revieweo</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="auth-bg" style="display:flex;flex-direction:column;align-items:stretch;justify-content:flex-start;">
    <main style="flex:1;width:100%;display:flex;align-items:center;justify-content:center;padding:32px 16px;">
        <div class="auth-container">
            <div class="auth-animated-glow"></div>
            <div class="auth-content">
                <div class="auth-title">Creer un compte</div>
                <?php if ($error) echo $error; ?>
                <?php if ($message) echo $message; ?>
                <form class="auth-form" method="POST" autocomplete="off">
                    <input name="pseudo" placeholder="Pseudo" required maxlength="32">
                    <input name="email" placeholder="Email" type="email" required maxlength="64">
                    <input type="password" name="password" placeholder="Mot de passe" required minlength="6" maxlength="64">
                    <button type="submit">S'inscrire</button>
                </form>
                <div class="auth-footer-link">
                    <a href="/revieweo/auth/login.php">Deja un compte ? Se connecter</a>
                </div>
            </div>
        </div>
    </main>
    <?php require_once '../includes/footer.php'; ?>
</body>
</html>

