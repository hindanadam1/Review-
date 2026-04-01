<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user']['id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$message = "";
$userId = (int) $_SESSION['user']['id'];
$isAdmin = isset($_SESSION['user']['role']) && (int) $_SESSION['user']['role'] === 2;
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die('ID manquant');
}

$selectQuery = $isAdmin
    ? "SELECT * FROM critique WHERE id = ?"
    : "SELECT * FROM critique WHERE id = ? AND id_user = ?";
$stmt = $pdo->prepare($selectQuery);
$stmt->execute($isAdmin ? [$id] : [$id, $userId]);
$critique = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$critique) {
    die('Critique introuvable');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $note = (int) ($_POST['note'] ?? 0);

    if ($titre !== '' && $contenu !== '' && $note >= 0 && $note <= 5) {
        $updateQuery = $isAdmin
            ? "UPDATE critique SET titre = ?, contenu = ?, note = ? WHERE id = ?"
            : "UPDATE critique SET titre = ?, contenu = ?, note = ? WHERE id = ? AND id_user = ?";
        $stmt = $pdo->prepare($updateQuery);
        $updated = $isAdmin
            ? $stmt->execute([$titre, $contenu, $note, $id])
            : $stmt->execute([$titre, $contenu, $note, $id, $userId]);

        if ($updated) {
            $message = "Critique modifiee !";
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
            <?php if ($message) echo '<div class="success-msg">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>'; ?>
            <form class="register-form" method="POST">
                <input type="text" name="titre" value="<?= htmlspecialchars($critique['titre'], ENT_QUOTES, 'UTF-8') ?>" required>
                <textarea name="contenu" required><?= htmlspecialchars($critique['contenu'], ENT_QUOTES, 'UTF-8') ?></textarea>
                <input type="number" name="note" min="0" max="5" value="<?= (int) $critique['note'] ?>" required>
                <button type="submit">Modifier</button>
            </form>
            <?php if ($isAdmin): ?>
                <div class="auth-footer-link">
                    <a href="/revieweo/pages/admin_reviews.php">Retour a Admin Reviews</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
