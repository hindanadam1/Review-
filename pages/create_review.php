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
    <style>
    body.create-review-page {
        background: #111;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        font-family: 'Segoe UI', Arial, sans-serif;
    }

    .create-review-page main {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
    }

    .create-review-page .register-container {
        background: rgba(20, 20, 20, 0.98);
        border-radius: 20px;
        box-shadow: 0 0 40px 5px #900;
        padding: 40px 32px 32px;
        width: 100%;
        max-width: 620px;
        position: relative;
        overflow: hidden;
        margin: 40px auto;
    }

    .create-review-page .register-title {
        font-size: 2.2em;
        font-weight: bold;
        color: #ff2222;
        margin-bottom: 24px;
        letter-spacing: 2px;
        text-align: center;
    }

    .create-review-page .animated-glow {
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

    .create-review-page .register-content {
        position: relative;
        z-index: 1;
    }

    .create-review-page .register-form {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 16px !important;
        width: 100% !important;
    }

    .create-review-page .register-form input,
    .create-review-page .register-form textarea,
    .create-review-page .register-form select,
    .create-review-page .register-form button {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        margin: 0 !important;
    }

    .create-review-page .register-form input,
    .create-review-page .register-form textarea,
    .create-review-page .register-form select {
        padding: 16px 18px !important;
        border: none !important;
        border-radius: 12px !important;
        background: #222 !important;
        color: #fff !important;
        font-size: 1em !important;
        resize: none !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        box-shadow: none !important;
    }

    .create-review-page .register-form textarea {
        min-height: 120px !important;
    }

    .create-review-page .register-form input:focus,
    .create-review-page .register-form textarea:focus,
    .create-review-page .register-form select:focus {
        outline: none;
        box-shadow: 0 0 0 2px #ff2222;
    }

    .create-review-page .register-form select option {
        background: #222 !important;
        color: #fff !important;
    }

    .create-review-page .register-form button {
        padding: 14px !important;
        background: linear-gradient(90deg, #900, #ff2222) !important;
        color: #fff !important;
        border: none !important;
        border-radius: 12px !important;
        font-size: 1.15em !important;
        font-weight: bold !important;
        cursor: pointer !important;
        margin-top: 4px !important;
        box-shadow: 0 2px 8px #900a !important;
        transition: background 0.2s, transform 0.2s !important;
    }

    .create-review-page .register-form button:hover {
        background: linear-gradient(90deg, #ff2222, #900);
        transform: scale(1.02);
    }
    </style>
</head>
<body class="create-review-page">
    <main>
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

