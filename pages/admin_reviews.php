<?php
session_start();
require_once '../config/db.php';

$authService->requireAdmin('../auth/login.php');
$reviews = $reviewService->getAllWithLikesForViewer($authService->userId());
$totalReviews = count($reviews);
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
    background: #151515;
    color: #ffffff;
    font-family: "Roboto", sans-serif;
}
a {
    text-decoration: none;
}
.admin-navbar {
    background: #000;
    padding: 1rem 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
}
.admin-navbar .navbar-brand {
    color: #fff;
    font-size: 1.5rem;
    letter-spacing: 1px;
}
.admin-nav-links {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.admin-nav-links a {
    padding: 10px 16px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.06);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.admin-nav-links a.active,
.admin-nav-links a:hover {
    background: linear-gradient(135deg, #e53935 0%, #a30f0b 100%);
    color: #fff;
}
.admin-hero {
    background:
        linear-gradient(to bottom, rgba(0, 0, 0, 0.2), #151515),
        url('img/f-2.jpg');
    background-size: cover;
    background-position: center;
    min-height: 54vh;
    padding: 90px 0 70px;
    display: flex;
    align-items: flex-end;
}
.hero-kicker {
    text-transform: uppercase;
    letter-spacing: 4px;
    color: #ff8f8a;
    font-size: 0.9rem;
    margin-bottom: 10px;
}
.hero-title {
    font-size: 3.2rem;
    font-weight: 800;
    margin-bottom: 14px;
}
.hero-copy {
    max-width: 720px;
    color: #d3d3d3;
    line-height: 1.7;
}
.admin-content {
    padding: 34px 0 60px;
}
.section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-bottom: 22px;
}
.section-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0;
}
.section-copy {
    color: #b7b7b7;
    margin: 6px 0 0;
}
.review-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 72px;
    padding: 10px 16px;
    border-radius: 999px;
    background: rgba(255, 34, 34, 0.12);
    border: 1px solid rgba(255, 34, 34, 0.22);
    color: #ff6f69;
    font-weight: 800;
}
.review-card {
    border: none;
    border-radius: 18px;
    background: linear-gradient(180deg, rgba(34, 34, 34, 0.98) 0%, rgba(20, 20, 20, 1) 100%);
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border-top: 3px solid #c3110c;
    height: 100%;
}
.review-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 22px 50px rgba(0, 0, 0, 0.42);
}
.review-card .card-body {
    padding: 1.5rem;
}
.review-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
}
.review-badge {
    background: linear-gradient(135deg, #e53935 0%, #c3110c 100%);
    color: #fff;
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 700;
}
.review-owner {
    color: #ffb2ad;
    font-weight: 700;
    margin-bottom: 8px;
}
.review-date {
    color: #9a9a9a;
    font-size: 0.9rem;
    margin-bottom: 12px;
}
.review-content {
    color: #d2d2d2;
    line-height: 1.7;
    white-space: pre-line;
}
.review-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 18px;
}
.like-chip {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.like-btn {
    border: none;
    background: transparent;
    color: #9f9f9f;
    font-size: 1.2rem;
    line-height: 1;
    cursor: pointer;
    padding: 0;
    transition: transform 0.18s ease, color 0.18s ease;
}
.like-btn:hover {
    transform: scale(1.08);
}
.like-btn.liked {
    color: #ff4b4b;
}
.like-btn.pop {
    animation: likePop 0.35s ease;
}
.like-count {
    color: #fff;
    font-weight: 700;
    min-width: 14px;
}
.like-label {
    color: #d2d2d2;
    font-size: 0.95rem;
    font-weight: 600;
}
.unlike-btn {
    border: none;
    background: transparent;
    color: #ff8f8a;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    padding: 0;
    text-decoration: underline;
}
.unlike-btn:hover {
    color: #fff;
}
.btn-admin-delete {
    width: auto;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    padding: 11px 16px;
    background: linear-gradient(135deg, #e53935 0%, #a30f0b 100%);
    color: #fff;
}
.btn-admin-delete:hover {
    color: #fff;
    background: linear-gradient(135deg, #c3110c 0%, #7d0906 100%);
}
@keyframes likePop {
    0% { transform: scale(1); }
    40% { transform: scale(1.35); }
    100% { transform: scale(1); }
}
.empty-panel {
    background: linear-gradient(180deg, rgba(34, 34, 34, 0.98) 0%, rgba(20, 20, 20, 1) 100%);
    border-radius: 20px;
    padding: 54px 28px;
    text-align: center;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
    border-top: 3px solid #c3110c;
}
.empty-panel h2 {
    color: #fff;
    font-weight: 800;
}
.empty-panel p {
    color: #bdbdbd;
}
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.3rem;
    }
    .section-heading {
        flex-direction: column;
        align-items: flex-start;
    }
}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg admin-navbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/revieweo/pages/index.php">REVIEWEO</a>
            <div class="ms-auto admin-nav-links">
                <a class="active" href="/revieweo/pages/admin_reviews.php">Moderation Reviews</a>
                <a href="/revieweo/pages/admin_users.php">Gestion Utilisateurs</a>
                <a href="/revieweo/pages/dashboard.php">Retour Dashboard</a>
                <a href="/revieweo/auth/logout.php">Deconnexion</a>
            </div>
        </div>
    </nav>

    <section class="admin-hero">
        <div class="container">
            <div class="hero-kicker">Console administration</div>
            <h1 class="hero-title">Supervision complete des reviews</h1>
            <p class="hero-copy">Visualise toutes les critiques de la plateforme, identifie leur auteur instantanement et modere le contenu en gardant le meme univers visuel que le reste de Revieweo.</p>
        </div>
    </section>

    <section class="admin-content">
        <div class="container">
            <div class="section-heading">
                <div>
                    <h2 class="section-title">Flux global des reviews</h2>
                    <p class="section-copy">Chaque carte affiche la critique, son auteur et les informations utiles de moderation.</p>
                </div>
                <div class="review-count"><?= $totalReviews ?> reviews</div>
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
                            <div class="card review-card">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                        <h2 class="review-title"><?= htmlspecialchars($review['titre'], ENT_QUOTES, 'UTF-8') ?></h2>
                                        <span class="review-badge"><?= (int) $review['note'] ?>/5</span>
                                    </div>
                                    <div class="review-owner"><?= htmlspecialchars($review['pseudo'] ?? 'Utilisateur inconnu', ENT_QUOTES, 'UTF-8') ?> • <?= htmlspecialchars($review['email'] ?? 'email inconnu', ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="review-date">Publiee le <?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?> • User ID <?= (int) $review['id_user'] ?></div>
                                    <div class="review-content flex-grow-1"><?= htmlspecialchars($preview, ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="review-actions">
                                        <div class="like-chip">
                                            <button class="like-btn <?= (int) $review['user_liked'] === 1 ? 'liked' : '' ?>" data-id="<?= (int) $review['id'] ?>" type="button" aria-label="Aimer cette critique">❤️</button>
                                            <span class="like-count" id="like-count-<?= (int) $review['id'] ?>"><?= (int) $review['likes_count'] ?></span>
                                            <span class="like-label">likes</span>
                                            <button class="unlike-btn" data-id="<?= (int) $review['id'] ?>" type="button" style="<?= (int) $review['user_liked'] === 1 ? '' : 'display:none;' ?>">Retirer mon like</button>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <a class="btn btn-outline-light btn-sm" href="/revieweo/pages/review_detail.php?id=<?= (int) $review['id'] ?>">Voir la critique</a>
                                            <a class="btn btn-admin-delete" href="/revieweo/pages/delete_review.php?id=<?= (int) $review['id'] ?>" onclick="return confirm('Supprimer cette critique ?');">Supprimer</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-panel">
                    <h2>Aucune review pour le moment</h2>
                    <p>Les critiques publiees apparaitront ici avec les actions de moderation.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once '../includes/footer.php'; ?>
    <script>
    document.querySelectorAll('.like-btn').forEach((button) => {
        button.addEventListener('click', function () {
            const critiqueId = this.dataset.id;
            const unlikeButton = document.querySelector('.unlike-btn[data-id="' + critiqueId + '"]');
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/revieweo/actions/like.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onload = () => {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);

                    if (data.error === 'not_logged') {
                        window.location.href = '/revieweo/auth/login.php';
                        return;
                    }

                    this.classList.remove('pop');
                    void this.offsetWidth;
                    this.classList.add('pop');

                    if (data.status === 'liked') {
                        this.classList.add('liked');
                        if (unlikeButton) unlikeButton.style.display = 'inline';
                    } else {
                        this.classList.remove('liked');
                        if (unlikeButton) unlikeButton.style.display = 'none';
                    }

                    document.getElementById('like-count-' + critiqueId).innerText = data.likes;
                }
            };

            xhr.send('id_critique=' + encodeURIComponent(critiqueId));
        });
    });

    document.querySelectorAll('.unlike-btn').forEach((button) => {
        button.addEventListener('click', function () {
            const critiqueId = this.dataset.id;
            const likeButton = document.querySelector('.like-btn[data-id="' + critiqueId + '"]');
            if (likeButton) {
                likeButton.click();
            }
        });
    });
    </script>
</body>
</html>


