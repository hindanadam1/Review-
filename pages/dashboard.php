<?php
session_start();
require_once '../config/db.php';

$pseudo = $_SESSION['user']['pseudo'] ?? 'Visiteur';
$critiques = [];

if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];

    $stmt = $pdo->prepare("
        SELECT id, titre, contenu, note, date_creation
        FROM critique
        WHERE id_user = ?
        ORDER BY date_creation DESC
    ");
    $stmt->execute([$userId]);
    $critiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$totalCritiques = count($critiques);

$noteMoyenne = 0;
if ($totalCritiques > 0) {
    $somme = 0;
    foreach ($critiques as $critique) {
        $somme += $critique['note'];
    }
    $noteMoyenne = round($somme / $totalCritiques, 1);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Revieweo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
/* Style global pour Revieweo - Noir & Rouge */
body {
    background: #111;
    color: #fff;
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    min-height: 100vh;
}
a {
    color: #ff2222;
    text-decoration: none;
    transition: color 0.2s;
}
a:hover {
    color: #fff;
    text-shadow: 0 0 8px #ff2222;
}
input, button, textarea, select {
    font-family: inherit;
}
::-webkit-scrollbar {
    width: 8px;
    background: #222;
}
::-webkit-scrollbar-thumb {
    background: #900;
    border-radius: 8px;
}
:root {
    --black: #050505;
    --dark-black: #0d0d0d;
    --red: #c3110c;
    --red-dark: #8f0d09;
    --red-light: #e53935;
    --white: #ffffff;
    --light: #f5f5f5;
    --gray: #bbbbbb;
}
body {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
    background-color: #f3f3f3;
    color: #111111;
}
a {
    text-decoration: none;
}
.custom-navbar {
    background: linear-gradient(180deg, #c3110c 0%, #7e0d09 45%, #1a0707 75%, #050505 100%);
    padding: 1rem 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
}
.custom-navbar .navbar-brand {
    color: var(--white);
    font-size: 1.5rem;
    letter-spacing: 1px;
}
.custom-navbar .navbar-brand:hover {
    color: #ffd7d5;
}
.welcome-text {
    color: var(--white);
    font-weight: 500;
}
.custom-logout-btn {
    border-radius: 8px;
    font-weight: 600;
}
.dashboard-hero {
    background: linear-gradient(180deg, #d41414 0%, #a30f0b 25%, #5f0a08 50%, #1c0807 75%, #050505 100%);
    color: var(--white);
    padding: 80px 0 90px;
    text-align: center;
}
.hero-content {
    max-width: 800px;
    margin: 0 auto;
}
.dashboard-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 15px;
    color: var(--white);
}
.dashboard-subtitle {
    font-size: 1.1rem;
    color: #f0dede;
    margin-bottom: 25px;
}
.custom-btn-primary {
    background: linear-gradient(135deg, #e53935 0%, #c3110c 100%);
    color: var(--white);
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 700;
    transition: 0.3s ease;
}
.custom-btn-primary:hover {
    background: linear-gradient(135deg, #c3110c 0%, #8f0d09 100%);
    color: var(--white);
}
.btn-outline-dark {
    border: 1px solid var(--black);
    color: var(--black);
    border-radius: 10px;
    font-weight: 600;
}
.btn-outline-dark:hover {
    background-color: var(--black);
    color: var(--white);
}
.btn-danger {
    background: linear-gradient(135deg, #d61c16 0%, #a00d09 100%);
    border: none;
    border-radius: 10px;
    font-weight: 600;
}
.btn-danger:hover {
    background: linear-gradient(135deg, #b9120d 0%, #7d0b07 100%);
}
.dashboard-content {
    margin-top: -40px;
    position: relative;
    z-index: 2;
}
.stats-card {
    background: var(--white);
    border-radius: 18px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    border-top: 5px solid var(--red);
}
.stats-card h3 {
    font-size: 1.1rem;
    margin-bottom: 10px;
    color: #222;
}
.stats-card p {
    font-size: 2rem;
    font-weight: 800;
    margin: 0;
    color: var(--red);
}
.critique-card {
    border: none;
    border-radius: 18px;
    overflow: hidden;
    background: var(--white);
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border-top: 5px solid var(--red);
}
.critique-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 30px rgba(0, 0, 0, 0.14);
}
.critique-card .card-body {
    padding: 1.4rem;
}
.card-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--black);
    margin-bottom: 0;
}
.card-text {
    color: #444;
    line-height: 1.6;
}
.critique-date {
    color: #777;
    font-size: 0.9rem;
    margin-bottom: 12px;
}
.note-badge {
    background: linear-gradient(135deg, #e53935 0%, #c3110c 100%);
    color: var(--white);
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 700;
}
.empty-state {
    background: var(--white);
    border-radius: 20px;
    padding: 50px 25px;
    text-align: center;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
    border-top: 5px solid var(--red);
    max-width: 750px;
    margin: 0 auto;
}
.empty-state h3 {
    color: var(--black);
    margin-bottom: 15px;
    font-weight: 700;
}
.empty-state p {
    color: #666;
    margin-bottom: 20px;
}
.custom-footer {
    background: linear-gradient(180deg, #1a0707 0%, #050505 100%);
    color: var(--white);
    text-align: center;
    padding: 18px 0;
    margin-top: 50px;
}
@media (max-width: 768px) {
    .dashboard-title {
        font-size: 2.1rem;
    }
    .dashboard-subtitle {
        font-size: 1rem;
    }
    .custom-btn-primary {
        width: 100%;
    }
    .welcome-text {
        display: none;
    }
}
.custom-footer {
    background: linear-gradient(180deg, #1a0707 0%, #050505 100%);
    color: #ffffff;
    padding: 50px 0 20px;
    margin-top: 60px;
}
.footer-logo {
    font-size: 1.5rem;
    font-weight: 800;
    color: #ffffff;
}
.footer-desc {
    color: #bbbbbb;
    font-size: 0.95rem;
}
.footer-title {
    font-weight: 700;
    margin-bottom: 10px;
    color: #ffffff;
}
.footer-links {
    list-style: none;
    padding: 0;
}
.footer-links li {
    margin-bottom: 6px;
}
.footer-links a {
    color: #bbbbbb;
    transition: 0.3s;
}
.footer-links a:hover {
    color: #c3110c;
}
.footer-divider {
    border-color: rgba(255,255,255,0.1);
    margin: 20px 0;
}
.footer-bottom {
    font-size: 0.9rem;
    color: #888;
}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../index.php">REVIEWEO</a>

        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="welcome-text">Bonjour, <?= htmlspecialchars($pseudo) ?></span>
            <a href="../Auth/logout.php" class="btn btn-light btn-sm custom-logout-btn">Déconnexion</a>
        </div>
    </div>
</nav>

<section class="dashboard-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="dashboard-title">Mon Dashboard</h1>
            <p class="dashboard-subtitle">
                Gère toutes tes critiques facilement depuis ton espace personnel.
            </p>
            <a href="create_review.php" class="btn custom-btn-primary">+ Ajouter une critique</a>
        </div>
    </div>
</section>

<section class="dashboard-content py-5">
    <div class="container">

        <div class="row g-4 mb-5">
            <div class="col-12 col-md-6">
                <div class="stats-card">
                    <h3>Nombre de critiques</h3>
                    <p><?= $totalCritiques ?></p>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="stats-card">
                    <h3>Note moyenne</h3>
                    <p><?= $noteMoyenne ?>/10</p>
                </div>
            </div>
        </div>

        <?php if ($totalCritiques > 0): ?>
            <div class="row g-4">
                <?php foreach ($critiques as $critique): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card critique-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title">
                                        <?= htmlspecialchars($critique['titre']) ?>
                                    </h5>
                                    <span class="note-badge"><?= htmlspecialchars($critique['note']) ?>/10</span>
                                </div>

                                <p class="critique-date">
                                    Publiée le <?= htmlspecialchars($critique['date_creation']) ?>
                                </p>

                                <p class="card-text flex-grow-1">
                                    <?= htmlspecialchars(substr($critique['contenu'], 0, 140)) ?>...
                                </p>

                                <div class="d-flex gap-2 mt-3">
                                    <a href="edit_review.php?id=<?= $critique['id'] ?>" class="btn btn-outline-dark w-100">
                                        Modifier
                                    </a>

                                    <a href="delete_review.php?id=<?= $critique['id'] ?>"
                                       class="btn btn-danger w-100"
                                       onclick="return confirm('Voulez-vous vraiment supprimer cette critique ?');">
                                        Supprimer
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>Tu n’as encore publié aucune critique</h3>
                <p>Commence dès maintenant en ajoutant ta première critique.</p>
                <a href="create_review.php" class="btn custom-btn-primary">Créer une critique</a>
            </div>
        <?php endif; ?>

    </div>
</section>
<?php require_once '../includes/footer.php'; ?>

</body>
</html>
