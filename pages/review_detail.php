<?php
session_start();
require_once '../config/db.php';

$reviewId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$viewerId = $authService->isLoggedIn() ? $authService->userId() : null;
$review = $reviewId > 0 ? $reviewService->findPublicByIdWithLikes($reviewId, $viewerId) : null;
$reviewCategories = $review ? $categoryService->getByReviewId((int) $review['id']) : [];

if (!$review) {
    http_response_code(404);
}

$currentUser = $authService->currentUser();
$pseudo = $currentUser['pseudo'] ?? 'Visiteur';
$isAdmin = $authService->isAdmin();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $review ? htmlspecialchars($review['titre'], ENT_QUOTES, 'UTF-8') : 'Critique introuvable' ?> - Revieweo</title>
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
.custom-navbar {
    background: #000;
    padding: 1rem 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
}
.custom-navbar .navbar-brand,
.welcome-text {
    color: #fff;
}
.custom-navbar .navbar-brand {
    font-size: 1.5rem;
    letter-spacing: 1px;
}
.custom-logout-btn {
    border-radius: 8px;
    font-weight: 700;
    background: linear-gradient(135deg, #e53935 0%, #a30f0b 100%);
    border: none;
    color: #fff;
}
.custom-admin-top-btn {
    border-radius: 8px;
    font-weight: 700;
    background: linear-gradient(135deg, #ffffff 0%, #f3dede 100%);
    color: #8f0d09;
    border: none;
}
.detail-hero {
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.15), #151515), url('img/f-2.jpg');
    background-size: cover;
    background-position: center;
    min-height: 46vh;
    padding: 90px 0 80px;
    display: flex;
    align-items: flex-end;
}
.hero-content {
    max-width: 820px;
}
.detail-back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #fff;
    font-weight: 700;
    margin-bottom: 22px;
}
.detail-back-link::before {
    content: "\2190";
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 999px;
    background: rgba(255, 34, 34, 0.18);
    border: 1px solid rgba(255, 34, 34, 0.35);
    color: #ff4b4b;
    font-size: 1rem;
}
.detail-kicker {
    text-transform: uppercase;
    letter-spacing: 4px;
    color: #ff8f8a;
    font-size: 0.9rem;
    margin-bottom: 10px;
}
.detail-title {
    font-size: 3.2rem;
    font-weight: 800;
    margin-bottom: 16px;
}
.detail-subtitle {
    color: #d3d3d3;
    font-size: 1.05rem;
    line-height: 1.7;
    margin-bottom: 0;
}
.detail-content {
    padding: 34px 0 70px;
}
.detail-card {
    border: none;
    border-radius: 24px;
    background: linear-gradient(180deg, rgba(34, 34, 34, 0.98) 0%, rgba(20, 20, 20, 1) 100%);
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
    border-top: 3px solid #c3110c;
    overflow: hidden;
}
.detail-card .card-body {
    padding: 2rem;
}
.detail-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 18px;
}
.detail-pill,
.note-badge,
.like-chip,
.category-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 16px;
    border-radius: 999px;
    font-weight: 700;
}
.detail-pill {
    background: rgba(255, 255, 255, 0.06);
    color: #ddd;
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.note-badge {
    background: linear-gradient(135deg, #e53935 0%, #c3110c 100%);
    color: #fff;
}
.category-pill {
    background: rgba(255, 34, 34, 0.12);
    color: #ffb2ad;
    border: 1px solid rgba(255, 34, 34, 0.22);
}
.detail-author {
    color: #ffb2ad;
    font-weight: 700;
    margin-bottom: 8px;
}
.detail-date {
    color: #9a9a9a;
    font-size: 0.95rem;
    margin-bottom: 24px;
}
.detail-text {
    color: #ececec;
    line-height: 1.9;
    font-size: 1.05rem;
    white-space: pre-line;
}
.detail-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 28px;
}
.like-chip {
    gap: 10px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.like-btn {
    border: none;
    background: transparent;
    color: #9f9f9f;
    font-size: 1.3rem;
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
.detail-owner-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.action-link {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #fff;
    font-weight: 700;
    background: transparent;
}
.action-link:hover {
    color: #fff;
    background: rgba(255, 34, 34, 0.12);
    border-color: rgba(255, 34, 34, 0.25);
}
.delete-link {
    width: 44px;
    height: 44px;
    padding: 0;
    justify-content: center;
}
.empty-panel {
    background: linear-gradient(180deg, rgba(34, 34, 34, 0.98) 0%, rgba(20, 20, 20, 1) 100%);
    border-radius: 20px;
    padding: 54px 28px;
    text-align: center;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
    border-top: 3px solid #c3110c;
    max-width: 760px;
    margin: 0 auto;
}
.empty-panel h2 {
    color: #fff;
    font-weight: 800;
    margin-bottom: 12px;
}
.empty-panel p {
    color: #bdbdbd;
    margin-bottom: 0;
}
@keyframes likePop {
    0% { transform: scale(1); }
    40% { transform: scale(1.35); }
    100% { transform: scale(1); }
}
@media (max-width: 768px) {
    .detail-title {
        font-size: 2.3rem;
    }

    .detail-actions {
        flex-direction: column;
        align-items: flex-start;
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
            <?php if ($authService->isLoggedIn()): ?>
                <span class="welcome-text">Bonjour, <?= htmlspecialchars($pseudo, ENT_QUOTES, 'UTF-8') ?></span>
                <?php if ($isAdmin): ?>
                    <a href="/revieweo/pages/admin_reviews.php" class="btn btn-sm custom-admin-top-btn">Espace Admin</a>
                <?php else: ?>
                    <a href="/revieweo/auth/logout.php" class="btn btn-sm custom-logout-btn">Deconnexion</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="/revieweo/auth/login.php" class="btn btn-sm custom-logout-btn">Se connecter</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<section class="detail-hero">
    <div class="container">
        <div class="hero-content">
            <a class="detail-back-link" href="/revieweo/pages/index.php">Retour a l'accueil</a>
            <div class="detail-kicker">Critique complete</div>
            <?php if ($review): ?>
                <h1 class="detail-title"><?= htmlspecialchars($review['titre'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="detail-subtitle">Une lecture complete de la critique publiee sur Revieweo, avec son auteur, sa note, sa categorie et ses interactions.</p>
            <?php else: ?>
                <h1 class="detail-title">Critique introuvable</h1>
                <p class="detail-subtitle">La critique que tu cherches n'existe pas ou n'est plus disponible.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="detail-content">
    <div class="container">
        <?php if ($review): ?>
            <?php
            $date = date('d/m/Y H:i', strtotime($review['date_creation']));
            $canManage = $authService->isLoggedIn() && ($isAdmin || (int) $review['id_user'] === (int) $authService->userId());
            ?>
            <div class="card detail-card">
                <div class="card-body">
                    <div class="detail-meta">
                        <span class="note-badge"><?= (int) $review['note'] ?>/5</span>
                        <span class="detail-pill">Critique #<?= (int) $review['id'] ?></span>
                        <?php foreach ($reviewCategories as $reviewCategory): ?>
                            <span class="category-pill"><?= htmlspecialchars($reviewCategory['nom'], ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="detail-author"><?= htmlspecialchars($review['pseudo'] ?? 'Utilisateur inconnu', ENT_QUOTES, 'UTF-8') ?><?php if (!empty($review['email'])): ?> &middot; <?= htmlspecialchars($review['email'], ENT_QUOTES, 'UTF-8') ?><?php endif; ?></div>
                    <div class="detail-date">Publiee le <?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="detail-text"><?= htmlspecialchars($review['contenu'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="detail-actions">
                        <div class="like-chip">
                            <button class="like-btn <?= (int) $review['user_liked'] === 1 ? 'liked' : '' ?>" data-id="<?= (int) $review['id'] ?>" type="button" aria-label="Aimer cette critique">&#10084;&#65039;</button>
                            <span class="like-count" id="like-count-<?= (int) $review['id'] ?>"><?= (int) $review['likes_count'] ?></span>
                            <span class="like-label">likes</span>
                        </div>
                        <?php if ($canManage): ?>
                            <div class="detail-owner-actions">
                                <a class="action-link" href="/revieweo/pages/edit_review.php?id=<?= (int) $review['id'] ?>">Modifier</a>
                                <a class="action-link delete-link" href="/revieweo/pages/delete_review.php?id=<?= (int) $review['id'] ?>" onclick="return confirm('Supprimer cette critique ?');" title="Supprimer cette critique">&#128465;</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-panel">
                <h2>Impossible d'ouvrir cette critique</h2>
                <p>Reviens a l'accueil ou au dashboard pour continuer ta navigation.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>

<script>
document.querySelectorAll('.like-btn').forEach((button) => {
    button.addEventListener('click', function () {
        const critiqueId = this.dataset.id;
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
                } else {
                    this.classList.remove('liked');
                }

                document.getElementById('like-count-' + critiqueId).innerText = data.likes;
            }
        };

        xhr.send('id_critique=' + encodeURIComponent(critiqueId));
    });
});
</script>
</body>
</html>

