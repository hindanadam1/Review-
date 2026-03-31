
<?php
include '../config/db.php';
$message = '';
if ($_POST) {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO user (pseudo, email, password, role) VALUES (?, ?, ?, 1)");
    $stmt->execute([$pseudo, $email, $password]);

    $message = "<div class='success-msg'>Compte créé ! <a href='login.php'>Se connecter</a></div>";
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
            <div class="auth-title">Créer un compte</div>
            <?php if ($message) echo $message; ?>
            <form class="auth-form" method="POST" autocomplete="off">
                <input name="pseudo" placeholder="Pseudo" required maxlength="32">
                <input name="email" placeholder="Email" type="email" required maxlength="64">
                <input type="password" name="password" placeholder="Mot de passe" required minlength="6" maxlength="64">
                <button type="submit">S'inscrire</button>
            </form>
            <div class="auth-footer-link">
                <a href="login.php">Déjà un compte ? Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>
