<?php
include '../config/db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $plainPassword = $_POST['password'] ?? '';

    if ($pseudo === '' || $email === '' || $plainPassword === '') {
        $error = "<div class='auth-error'>Veuillez remplir tous les champs.</div>";
    } else {
        $checkStmt = $pdo->prepare("SELECT id FROM user WHERE LOWER(email) = LOWER(?) OR pseudo = ? LIMIT 1");
        $checkStmt->execute([$email, $pseudo]);
        $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $error = "<div class='auth-error'>Un compte avec cet email ou ce pseudo existe deja.</div>";
        } else {
            $password = password_hash($plainPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO user (pseudo, email, password, role) VALUES (?, ?, ?, 1)");
            $stmt->execute([$pseudo, $email, $password]);
            $message = "<div class='success-msg'>Compte cree ! <a href='login.php'>Se connecter</a></div>";
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
<body class="auth-bg">
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
                <a href="login.php">Deja un compte ? Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>
