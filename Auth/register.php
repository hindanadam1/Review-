
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
    <style>
        body {
            background: #111;
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-container {
            background: rgba(20, 20, 20, 0.98);
            border-radius: 20px;
            box-shadow: 0 0 40px 5px #900;
            padding: 40px 32px 32px 32px;
            width: 350px;
            position: relative;
            overflow: hidden;
        }
        .register-title {
            font-size: 2.2em;
            font-weight: bold;
            color: #ff2222;
            margin-bottom: 24px;
            letter-spacing: 2px;
            text-align: center;
            position: relative;
        }
        .register-form input {
            width: 100%;
            padding: 12px 14px;
            margin: 12px 0;
            border: none;
            border-radius: 8px;
            background: #222;
            color: #fff;
            font-size: 1em;
            transition: box-shadow 0.2s;
        }
        .register-form input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #ff2222;
        }
        .register-form button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #900, #ff2222);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            margin-top: 18px;
            box-shadow: 0 2px 8px #900a;
            transition: background 0.2s, transform 0.2s;
        }
        .register-form button:hover {
            background: linear-gradient(90deg, #ff2222, #900);
            transform: scale(1.04);
        }
        .success-msg {
            background: #222;
            color: #0f0;
            border-left: 5px solid #ff2222;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            text-align: center;
        }
        /* Animation: floating red glow */
        .animated-glow {
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, #ff2222 0%, #900 60%, transparent 80%);
            filter: blur(32px);
            opacity: 0.7;
            animation: floatGlow 3s ease-in-out infinite alternate;
            z-index: 0;
        }
        @keyframes floatGlow {
            0% { transform: translateX(-50%) translateY(0); opacity: 0.7; }
            100% { transform: translateX(-50%) translateY(30px); opacity: 1; }
        }
        .register-content { position: relative; z-index: 1; }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="animated-glow"></div>
        <div class="register-content">
            <div class="register-title">Créer un compte</div>
            <?php if ($message) echo $message; ?>
            <form class="register-form" method="POST" autocomplete="off">
                <input name="pseudo" placeholder="Pseudo" required maxlength="32">
                <input name="email" placeholder="Email" type="email" required maxlength="64">
                <input type="password" name="password" placeholder="Mot de passe" required minlength="6" maxlength="64">
                <button type="submit">S'inscrire</button>
            </form>
            <div style="text-align:center;margin-top:18px;">
                <a href="login.php" style="color:#ff2222;text-decoration:underline;">Déjà un compte ? Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>