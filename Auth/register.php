<?php
include '../config/db.php';

if ($_POST) {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO user (pseudo, email, password, role) VALUES (?, ?, ?, 1)");
    $stmt->execute([$pseudo, $email, $password]);

    echo "Compte créé ! <a href='login.php'>Se connecter</a>";
}
?>

<h2>Inscription</h2>
<form method="POST">
    <input name="pseudo" placeholder="Pseudo"><br>
    <input name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Mot de passe"><br>
    <button>S'inscrire</button>
</form>