<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user']['id']) || !isset($_SESSION['user']['role'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ((int) $_SESSION['user']['role'] !== 2) {
    die('Acces reserve aux administrateurs.');
}

$stmt = $pdo->query("
    SELECT critique.id, critique.titre, critique.contenu, critique.note, critique.date_creation,
           critique.id_user, user.pseudo, user.email
    FROM critique
    LEFT JOIN user ON user.id = critique.id_user
    ORDER BY critique.date_creation DESC, critique.id DESC
");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalReviews = count($reviews);
$averageNote = 0;

if ($totalReviews > 0) {
    $sum = 0;
    foreach ($reviews as $review) {
        $sum += (int) $review['note'];
    }
    $averageNote = round($sum / $totalReviews, 1);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reviews | Revieweo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
body {
    margin: 0;
    min-height: 100vh;
    background:
        radial-gradient(circle at top, rgba(255, 30, 30, 0.18), transparent 30%),
        linear-gradient(180deg, #120404 0%, #090909 40%, #030303 100%);
    color: #ffffff;
    font-family: Arial, Helvetica, sans-serif;
}
a {
    text-decoration: none;
}
.admin-shell {
    padding: 32px 0 60px;
}
.admin-topbar {
    background: rgba(18, 18, 18, 0.92);
    border: 1px solid rgba(255, 40, 40, 0.16);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
    border-radius: 24px;
    padding: 20px 24px;
    margin-bottom: 28px;
}
.brand-mark {
    font-size: 1.6rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: #ff3b30;
}
.admin-nav {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.admin-nav a {
    padding: 10px 16px;
    border-radius: 999px;
    background: #1c1c1c;
    color: #f5d8d8;
    border: 1px solid rgba(255, 59, 48, 0.18);
}
.admin-nav a.active,
.admin-nav a:hover {
    background: linear-gradient(135deg, #ff3126 0%, #a30f0b 100%);
    color: #fff;
}
.hero-panel {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(255, 40, 40, 0.18) 0%, rgba(20, 20, 20, 0.96) 55%, rgba(8, 8, 8, 1) 100%);
    border-radius: 32px;
    padding: 42px 36px;
    border: 1px solid rgba(255, 65, 65, 0.18);
    box-shadow: 0 30px 90px rgba(0, 0, 0, 0.38);
    margin-bottom: 28px;
}
.hero-panel::before {
    content: "";
    position: absolute;
    top: -90px;
    right: -40px;
    width: 260px;
    height: 260px;
    background: radial-gradient(circle, rgba(255, 59, 48, 0.5), transparent 70%);
    filter: blur(8px);
}
.hero-label {
    text-transform: uppercase;
    letter-spacing: 3px;
    color: #ff8f8a;
    font-size: 0.85rem;
    margin-bottom: 12px;
}
.hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 14px;
}
.hero-copy {
    max-width: 720px;
    color: #f4c6c3;
    font-size: 1.05rem;
}
.stat-card {
    background: linear-gradient(180deg, rgba(30, 30, 30, 0.95) 0%, rgba(15, 15, 15, 0.98) 100%);
    border-radius: 24px;
    padding: 24px;
    border: 1px solid rgba(255, 55, 55, 0.14);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.04), 0 18px 44px rgba(0, 0, 0, 0.25);
    height: 100%;
}
.stat-card h3 {
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #ff9d99;
}
.stat-card p {
    margin: 12px 0 0;
    font-size: 2.4rem;
    font-weight: 800;
    color: #fff;
}
.review-card {
    background: linear-gradient(180deg, rgba(255,255,255,0.97) 0%, rgba(247,247,247,1) 100%);
    color: #161616;
    border: 1px solid rgba(255, 50, 50, 0.18);
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.28);
    height: 100%;
}
.review-meta {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: flex-start;
    margin-bottom: 14px;
}
.review-title {
    font-size: 1.35rem;
    font-weight: 800;
    margin: 0;
}
.review-badge {
    background: linear-gradient(135deg, #ff382d 0%, #b80f0a 100%);
    color: #fff;
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 700;
    white-space: nowrap;
}
.review-owner {
    color: #7a2121;
    font-weight: 700;
    margin-bottom: 8px;
}
.review-date {
    color: #666;
    font-size: 0.92rem;
    margin-bottom: 16px;
}
.review-content {
    color: #303030;
    line-height: 1.6;
    white-space: pre-line;
    min-height: 96px;
}
.action-row {
    margin-top: 20px;
}
.btn-admin-delete {
    background: linear-gradient(135deg, #ff3226 0%, #980c08 100%);
    color: #fff;
    border: none;
    width: 100%;
    border-radius: 12px;
    font-weight: 700;
    padding: 11px 16px;
}
.btn-admin-delete:hover {
    background: linear-gradient(135deg, #d31d14 0%, #7d0906 100%);
    color: #fff;
}
.empty-panel {
    background: rgba(255,255,255,0.96);
    color: #121212;
    border-radius: 24px;
    padding: 48px 24px;
    text-align: center;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.28);
}
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.2rem;
    }
}
    </style>
</head>
<body>
    <div class="container admin-shell">
        <div class="admin-topbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <div class="brand-mark">REVIEWEO ADMIN</div>
                <div class="text-light-emphasis">Gestion centralisee des critiques et des utilisateurs</div>
            </div>
            <div class="admin-nav">
                <a class="active" href="/revieweo/pages/admin_reviews.php">Admin Reviews</a>
                <a href="/revieweo/pages/admin_users.php">Admin Users</a>
                <a href="/revieweo/pages/dashboard.php">Dashboard</a>
                <a href="/revieweo/auth/logout.php">Deconnexion</a>
            </div>
        </div>

        <div class="hero-panel">
            <div class="hero-label">Moderation</div>
            <h1 class="hero-title">Toutes les reviews en un seul regard</h1>
            <p class="hero-copy">Depuis cet espace admin, tu peux voir toutes les critiques publiees avec leur auteur et supprimer rapidement une review si besoin.</p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6">
                <div class="stat-card">
                    <h3>Total reviews</h3>
                    <p><?= $totalReviews ?></p>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="stat-card">
                    <h3>Note moyenne globale</h3>
                    <p><?= $averageNote ?>/5</p>
                </div>
            </div>
        </div>

        <?php if ($totalReviews > 0): ?>
            <div class="row g-4">
                <?php foreach ($reviews as $review): ?>
                    <?php
                    $content = trim($review['contenu']);
                    $preview = mb_strlen($content) > 220 ? mb_substr($content, 0, 220) . '...' : $content;
                    $date = date('d/m/Y H:i', strtotime($review['date_creation']));
                    ?>
                    <div class="col-12 col-lg-6">
                        <div class="review-card">
                            <div class="review-meta">
                                <h2 class="review-title"><?= htmlspecialchars($review['titre'], ENT_QUOTES, 'UTF-8') ?></h2>
                                <span class="review-badge"><?= (int) $review['note'] ?>/5</span>
                            </div>
                            <div class="review-owner">
                                <?= htmlspecialchars($review['pseudo'] ?? 'Utilisateur inconnu', ENT_QUOTES, 'UTF-8') ?>
                                •
                                <?= htmlspecialchars($review['email'] ?? 'email inconnu', ENT_QUOTES, 'UTF-8') ?>
                            </div>
                            <div class="review-date">Publiee le <?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?> • User ID <?= (int) $review['id_user'] ?></div>
                            <div class="review-content"><?= htmlspecialchars($preview, ENT_QUOTES, 'UTF-8') ?></div>
                            <div class="action-row">
                                <a class="btn btn-admin-delete" href="/revieweo/pages/delete_review.php?id=<?= (int) $review['id'] ?>" onclick="return confirm('Supprimer cette critique ?');">Supprimer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-panel">
                <h2>Aucune review pour le moment</h2>
                <p>Les critiques publiees apparaitront ici avec les actions d'administration.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
