<?php
session_start();
require_once '../config/db.php';

$message = "";
$prefilledTitle = trim($_GET['title'] ?? '');
$selectedCategoryId = isset($_GET['category']) ? (int) $_GET['category'] : 0;
$categories = $categoryService->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $note = (int) ($_POST['note'] ?? -1);
    $selectedCategoryId = (int) ($_POST['id_categorie'] ?? 0);

    if (!$authService->isLoggedIn()) {
        $message = 'Vous devez etre connecte pour publier une critique.';
    } elseif ($selectedCategoryId <= 0) {
        $message = 'Choisis une categorie pour ta critique.';
    } elseif ($titre !== '' && $contenu !== '' && $note >= 0 && $note <= 5) {
        $reviewId = $reviewService->createAndReturnId($authService->userId(), $titre, $contenu, $note);

        if ($reviewId !== null && $categoryService->attachToReview($reviewId, $selectedCategoryId)) {
            header('Location: /revieweo/pages/dashboard.php');
            exit();
        }

        $message = "Erreur lors de l'enregistrement.";
    } else {
        $message = 'Champs invalides';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Creer une critique</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body style="background:#111;min-height:100vh;display:flex;flex-direction:column;font-family:'Segoe UI',Arial,sans-serif;">
    <main style="flex:1;display:flex;align-items:center;justify-content:center;padding:40px 16px;">
        <div class="register-container">
            <div class="animated-glow"></div>
            <div class="register-content">
                <a class="back-link" href="/revieweo/pages/dashboard.php">Retour</a>
                <div class="register-title">Creer une critique</div>
                <?php if ($message) echo '<div class="success-msg">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>'; ?>
                <form class="register-form" method="POST">
                    <input type="text" name="titre" placeholder="Titre" value="<?= htmlspecialchars($prefilledTitle, ENT_QUOTES, 'UTF-8') ?>" required>
                    <select class="category-select" name="id_categorie" required>
                        <option value="">Choisir une categorie</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['id'] ?>" <?= $selectedCategoryId === (int) $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['nom'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <textarea name="contenu" placeholder="Votre critique..." required></textarea>
                    <input type="number" name="note" min="0" max="5" placeholder="Note /5" required>
                    <button type="submit">Publier</button>
                </form>
            </div>
        </div>
    </main>
    <?php require_once '../includes/footer.php'; ?>
</body>
</html>

