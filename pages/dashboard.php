<?php
session_start();
require_once '../config/db.php';

// Cette page est priv�e : sans session, retour vers la page de connexion.

$authService->requireLogin('../auth/login.php');

$currentUser = $authService->currentUser();
$userId = $authService->userId();
$pseudo = $currentUser['pseudo'] ?? 'Visiteur';
$isAdmin = $authService->isAdmin();

$critiques = $reviewService->getByUserIdWithLikes($userId);
$totalCritiques = count($critiques);
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
.custom-logout-btn:hover {
    color: #fff;
    background: linear-gradient(135deg, #c3110c 0%, #7d0906 100%);
}
.custom-admin-top-btn {
    border-radius: 8px;
    font-weight: 700;
    background: linear-gradient(135deg, #ffffff 0%, #f3dede 100%);
    color: #8f0d09;
    border: none;
}
.dashboard-hero {
    background:
        linear-gradient(to bottom, rgba(0, 0, 0, 0.15), #151515),
        url('img/f-2.jpg');
    background-size: cover;
    background-position: center;
    min-height: 58vh;
    padding: 90px 0 80px;
    display: flex;
    align-items: flex-end;
}
.hero-content {
    max-width: 760px;
}
.dashboard-back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #fff;
    font-weight: 700;
    margin-bottom: 22px;
}
.dashboard-back-link::before {
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
    transition: transform 0.2s ease, background 0.2s ease;
}
.dashboard-back-link:hover {
    color: #fff;
}
.dashboard-back-link:hover::before {
    transform: translateX(-3px);
    background: rgba(255, 34, 34, 0.28);
}
.dashboard-kicker {
    text-transform: uppercase;
    letter-spacing: 4px;
    color: #ff8f8a;
    font-size: 0.9rem;
    margin-bottom: 10px;
}
.dashboard-title {
    font-size: 3.4rem;
    font-weight: 800;
    margin-bottom: 16px;
}
.dashboard-subtitle {
    width: min(640px, 100%);
    color: #d3d3d3;
    font-size: 1.05rem;
    line-height: 1.7;
    margin-bottom: 0;
}
.dashboard-content {
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
.critique-card {
    position: relative;
    overflow: hidden;
    border: none;
    border-radius: 18px;
    background: linear-gradient(180deg, rgba(34, 34, 34, 0.98) 0%, rgba(20, 20, 20, 1) 100%);
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border-top: 3px solid #c3110c;
}
.critique-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 22px 50px rgba(0, 0, 0, 0.42);
}
.critique-card .card-body {
    padding: 1.5rem;
}
.card-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 0;
}
.card-text {
    color: #d2d2d2;
    line-height: 1.7;
    white-space: pre-line;
}
.card-actions {
    margin-top: 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.card-action-buttons {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.edit-review-link {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 999px;
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #fff;
    font-weight: 700;
    transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
}
.edit-review-link::before {
    content: "\270E";
    color: #ff7d77;
    font-size: 0.95rem;
}
.edit-review-link:hover {
    color: #fff;
    background: rgba(255, 34, 34, 0.12);
    border-color: rgba(255, 34, 34, 0.25);
    transform: translateY(-2px);
}
.delete-review-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 999px;
    background: rgba(255, 34, 34, 0.12);
    border: 1px solid rgba(255, 34, 34, 0.25);
    color: #fff;
    transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
}
.delete-review-link::before {
    content: "\1F5D1";
    color: #ff7d77;
    font-size: 1rem;
}
.delete-review-link:hover {
    color: #fff;
    background: rgba(255, 34, 34, 0.2);
    border-color: rgba(255, 34, 34, 0.35);
    transform: translateY(-2px);
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
@keyframes likePop {
    0% { transform: scale(1); }
    40% { transform: scale(1.35); }
    100% { transform: scale(1); }
}
.critique-date {
    color: #9a9a9a;
    font-size: 0.9rem;
    margin-bottom: 12px;
}
.note-badge {
    background: linear-gradient(135deg, #e53935 0%, #c3110c 100%);
    color: #fff;
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 700;
}
.empty-state {
    background: linear-gradient(180deg, rgba(34, 34, 34, 0.98) 0%, rgba(20, 20, 20, 1) 100%);
    border-radius: 20px;
    padding: 54px 28px;
    text-align: center;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
    border-top: 3px solid #c3110c;
    max-width: 760px;
    margin: 0 auto;
}
.empty-state h3 {
    color: #fff;
    margin-bottom: 15px;
    font-weight: 800;
    font-size: 2rem;
}
.empty-state p {
    color: #bdbdbd;
    margin-bottom: 0;
}
@media (max-width: 768px) {
    .dashboard-title {
        font-size: 2.4rem;
    }
    .dashboard-subtitle {
        font-size: 1rem;
    }
    .welcome-text {
        display: none;
    }
    .section-heading {
        flex-direction: column;
        align-items: flex-start;
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
            <?php if ($isAdmin): ?>
                <a href="admin_reviews.php" class="btn btn-sm custom-admin-top-btn">Espace Admin</a>
            <?php else: ?>
                <a href="/revieweo/auth/logout.php" class="btn btn-sm custom-logout-btn">Deconnexion</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<section class="dashboard-hero">
    <div class="container">
        <div class="hero-content">
            <a class="dashboard-back-link" href="/revieweo/pages/index.php">Retour a l'accueil</a>
            <div class="dashboard-kicker">Espace personnel</div>
            <h1 class="dashboard-title">Tes critiques, comme une vraie collection cinema</h1>
            <p class="dashboard-subtitle">
                Explore tout ce que tu as deja publie dans une ambiance plus proche de l'accueil, plus immersive, plus nette, plus Revieweo.
            </p>
        </div>
    </div>
</section>

<section class="dashboard-content">
    <div class="container">
        <div class="section-heading">
            <div>
                <h2 class="section-title">Bibliotheque des reviews</h2>
                <p class="section-copy">Retrouve chaque avis publie, du plus recent au plus ancien.</p>
            </div>
            <div class="review-count"><?= $totalCritiques ?> review<?= $totalCritiques > 1 ? 's' : '' ?></div>
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
                                <div class="card-actions">
                                    <div class="like-chip">
                                        <button class="like-btn <?= (int) $critique['user_liked'] === 1 ? 'liked' : '' ?>" data-id="<?= (int) $critique['id'] ?>" type="button" aria-label="Aimer cette critique">❤️</button>
                                        <span class="like-count" id="like-count-<?= (int) $critique['id'] ?>"><?= (int) $critique['likes_count'] ?></span>
                                        <span class="like-label">likes</span>
                                    </div>
                                    <div class="card-action-buttons">
                                        <a class="edit-review-link" href="/revieweo/pages/review_detail.php?id=<?= (int) $critique['id'] ?>">
                                            Voir la critique
                                        </a>
                                        <a class="edit-review-link" href="/revieweo/pages/edit_review.php?id=<?= (int) $critique['id'] ?>">
                                            Modifier
                                        </a>
                                        <a class="delete-review-link" href="/revieweo/pages/delete_review.php?id=<?= (int) $critique['id'] ?>" onclick="return confirm('Supprimer cette critique ?');" aria-label="Supprimer cette critique" title="Supprimer cette critique"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>Ta bibliotheque est encore vide</h3>
                <p>Depuis l'accueil, clique sur un film pour commencer ta premiere review et donner vie a ton espace.</p>
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



