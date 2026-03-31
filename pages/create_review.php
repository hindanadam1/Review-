<?php
require_once '../config/db.php';
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $contenu = trim($_POST['contenu']);
    $note = intval($_POST['note']);
    // Remplacer par $_SESSION['user_id'] en prod
    $user_id = 1; // à remplacer par l'id de l'utilisateur connecté
    if (!empty($titre) && !empty($contenu) && $note >= 0 && $note <= 5) {
        $stmt = $pdo->prepare("INSERT INTO critique (titre, contenu, note, date_creation, id_user) VALUES (?, ?, ?, NOW(), ?)");
        if ($stmt->execute([$titre, $contenu, $note, $user_id])) {
            $message = "Critique publiée !";
        } else {
            $message = "Erreur lors de l'enregistrement.";
        }
    } else {
        $message = "Champs invalides";
    }
}
?>
 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une critique</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body style="background:#111;min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',Arial,sans-serif;">
    <div class="register-container">
        <div class="animated-glow"></div>
        <div class="register-content">
            <div class="register-title">Créer une critique</div>
            <?php if ($message) echo '<div class="success-msg">' . $message . '</div>'; ?>
            <form class="register-form" method="POST">
                <input type="text" name="titre" placeholder="Titre" required>
                <textarea name="contenu" placeholder="Votre critique..." required></textarea>
                <input type="number" name="note" min="0" max="5" placeholder="Note /5" required>
                <button type="submit">Publier</button>
            </form>
        </div>
    </div>
</body>
</html>