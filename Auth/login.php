<?php
session_start();
include '../config/db.php';

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier si utilisateur existe
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Vérifier mot de passe
    if ($user && password_verify($password, $user['password'])) {

        // Stocker en session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'pseudo' => $user['pseudo'],
            'role' => $user['role']
        ];

        // Redirection
        header("Location: ../pages/index.php");
        exit();

    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>