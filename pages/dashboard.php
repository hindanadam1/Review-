<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user']['id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$userId = (int) $_SESSION['user']['id'];
$pseudo = $_SESSION['user']['pseudo'] ?? 'Visiteur';
$isAdmin = isset($_SESSION['user']['role']) && (int) $_SESSION['user']['role'] === 2;
$critiques = [];

$stmt = $pdo->prepare("
    SELECT id, titre, contenu, note, date_creation
    FROM critique
    WHERE id_user = ?
    ORDER BY date_creation DESC, id DESC
");
$stmt->execute([$userId]);
$critiques = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalCritiques = count($critiques);
$noteMoyenne = 0;

if ($totalCritiques > 0) {
    $somme = 0;
    foreach ($critiques as $critique) {
        $somme += (int) $critique['note'];
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
body {
    margin: 0;
    min-height: 100vh;
    background-color: #f3f3f3;
    color: #111111;
    font-family: Arial, Helvetica, sans-serif;
}
a {
    text-decoration: none;
}
:root {
    --black: #050505;
    --red: #c3110c;
    --red-dark: #8f0d09;
    --white: #ffffff;
}
.custom-navbar {
    background: linear-gradient(180deg, #c3110c 0%, #7e0d09 45%, #1a0707 75%, #050505 100%);
    padding: 1rem 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
}
.custom-navbar .navbar-brand,
.welcome-text {
    color: var(--white);
}
.custom-navbar .navbar-brand {
    font-size: 1.5rem;
    letter-spacing: 1px;
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
}
.custom-btn-primary:hover {
    background: linear-gradient(135deg, #c3110c 0%, #8f0d09 100%);
    color: var(--white);
}
.custom-btn-admin {
    background: transparent;
    color: var(--white);
    border: 1px solid rgba(255, 255, 255, 0.55);
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 700;
    margin-left: 12px;
}
.custom-btn-admin:hover {
    background: rgba(255, 255, 255, 0.08);
    color: var(--white);
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
    white-space: pre-line;
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
    color: #ffffff;
    padding: 50px 0 20px;
    margin-top: 60px;
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
    .custom-btn-admin {
        width: 100%;
        margin-left: 0;
        margin-top: 12px;
    }
    .welcome-text {
        display: none;
    }
}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/revieweo/pages/index.php">REVIEWEO</a>

        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="welcome-text">Bonjour, <?= htmlspecialchars($pseudo, ENT_QUOTES, 'UTF-8') ?></span>
            <a href="/revieweo/auth/logout.php" class="btn btn-light btn-sm custom-logout-btn">Deconnexion</a>
        </div>
    </div>
</nav>

<section class="dashboard-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="dashboard-title">Mon Dashboard</h1>
            <p class="dashboard-subtitle">
                Retrouve toutes tes critiques publiees depuis ton espace personnel.
            </p>
            <a href="create_review.php" class="btn custom-btn-primary">+ Ajouter une critique</a>
            <?php if ($isAdmin): ?>
                <a href="admin_reviews.php" class="btn custom-btn-admin">Admin</a>
            <?php endif; ?>
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
                    <p><?= $noteMoyenne ?>/5</p>
                </div>
            </div>
        </div>

        <?php if ($totalCritiques > 0): ?>
            <div class="row g-4">
                <?php foreach ($critiques as $critique): ?>
                    <?php
                    $contenu = trim($critique['contenu']);
                    $resume = mb_strlen($contenu) > 180 ? mb_substr($contenu, 0, 180) . '...' : $contenu;
                    $dateFormatee = date('d/m/Y H:i', strtotime($critique['date_creation']));
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card critique-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
                                    <h5 class="card-title"><?= htmlspecialchars($critique['titre'], ENT_QUOTES, 'UTF-8') ?></h5>
                                    <span class="note-badge"><?= (int) $critique['note'] ?>/5</span>
                                </div>

                                <p class="critique-date">Publiee le <?= htmlspecialchars($dateFormatee, ENT_QUOTES, 'UTF-8') ?></p>

                                <p class="card-text flex-grow-1"><?= htmlspecialchars($resume, ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>Tu n'as encore publie aucune critique</h3>
                <p>Commence des maintenant en ajoutant ta premiere critique.</p>
                <a href="create_review.php" class="btn custom-btn-primary">Creer une critique</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
