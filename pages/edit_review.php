<?php
require_once '../config/db.php';
$message = "";

// Récupérer l'ID de la critique à éditer
if (!isset($_GET['id'])) {
    die('ID manquant');
}
$id = intval($_GET['id']);

// Charger la critique existante
$stmt = $pdo->prepare("SELECT * FROM critique WHERE id = ?");
$stmt->execute([$id]);
$critique = $stmt->fetch();
if (!$critique) {
    die('Critique introuvable');
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $contenu = trim($_POST['contenu']);
    $note = intval($_POST['note']);
    if (!empty($titre) && !empty($contenu) && $note >= 0 && $note <= 5) {
        $stmt = $pdo->prepare("UPDATE critique SET titre = ?, contenu = ?, note = ? WHERE id = ?");
        if ($stmt->execute([$titre, $contenu, $note, $id])) {
            $message = "Critique modifiée !";
            // Recharger les données modifiées
            $critique['titre'] = $titre;
            $critique['contenu'] = $contenu;
            $critique['note'] = $note;
        } else {
            $message = "Erreur lors de la modification.";
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
    <title>Modifier critique</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body style="background:#111;min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',Arial,sans-serif;">
    <div class="register-container">
        <div class="animated-glow"></div>
        <div class="register-content">
            <div class="register-title">Modifier la critique</div>
            <?php if ($message) echo '<div class="success-msg">' . $message . '</div>'; ?>
            <form class="register-form" method="POST">
                <input type="text" name="titre" value="<?= isset($critique) ? htmlspecialchars($critique['titre']) : '' ?>" required>
                <textarea name="contenu" required><?= isset($critique) ? htmlspecialchars($critique['contenu']) : '' ?></textarea>
                <input type="number" name="note" min="0" max="5" value="<?= isset($critique) ? $critique['note'] : '' ?>" required>
                <button type="submit">Modifier</button>
            </form>
        </div>
    </div>
</body>
</html>