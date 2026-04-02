<?php
session_start();
require_once '../config/db.php';

$authService->requireAdmin('../auth/login.php');

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    if ($deleteId !== $authService->userId()) {
        $userService->deleteById($deleteId);
    }
    header('Location: admin_users.php');
    exit();
}

$users = $userService->getAllWithReviewCounts();
$totalUsers = count($users);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users | Revieweo</title>
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
    min-height: 48vh;
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
    font-size: 3rem;
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
.users-count {
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
.users-panel {
    background: linear-gradient(180deg, rgba(34, 34, 34, 0.98) 0%, rgba(20, 20, 20, 1) 100%);
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
    border-top: 3px solid #c3110c;
}
.users-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px;
}
.users-table thead th {
    color: #ffb2ad;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1.6px;
    padding: 0 18px 8px;
}
.users-table tbody tr {
    background: rgba(255, 255, 255, 0.98);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}
.users-table tbody td {
    padding: 18px;
    color: #191919;
    vertical-align: middle;
}
.users-table tbody td:first-child {
    border-radius: 18px 0 0 18px;
}
.users-table tbody td:last-child {
    border-radius: 0 18px 18px 0;
}
.user-name {
    font-weight: 800;
    margin-bottom: 4px;
}
.user-email {
    color: #666;
}
.role-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 700;
}
.role-admin {
    background: rgba(255, 48, 38, 0.14);
    color: #9a0e09;
}
.role-user {
    background: rgba(17, 17, 17, 0.08);
    color: #222;
}
.btn-delete-user {
    display: inline-block;
    background: linear-gradient(135deg, #e53935 0%, #a30f0b 100%);
    color: #fff;
    padding: 10px 16px;
    border-radius: 12px;
    font-weight: 700;
}
.btn-delete-user:hover {
    color: #fff;
    background: linear-gradient(135deg, #c3110c 0%, #7d0906 100%);
}
.self-label {
    color: #7a2222;
    font-weight: 700;
}
@media (max-width: 992px) {
    .users-table thead {
        display: none;
    }
    .users-table,
    .users-table tbody,
    .users-table tr,
    .users-table td {
        display: block;
        width: 100%;
    }
    .users-table tbody tr {
        margin-bottom: 14px;
        border-radius: 20px;
        overflow: hidden;
    }
    .users-table tbody td {
        border-radius: 0 !important;
        padding: 14px 16px;
    }
    .hero-title {
        font-size: 2.2rem;
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
                <a href="/revieweo/pages/admin_reviews.php">Moderation Reviews</a>
                <a class="active" href="/revieweo/pages/admin_users.php">Gestion Utilisateurs</a>
                <a href="/revieweo/pages/dashboard.php">Retour Dashboard</a>
                <a href="/revieweo/auth/logout.php">Deconnexion</a>
            </div>
        </div>
    </nav>

    <section class="admin-hero">
        <div class="container">
            <div class="hero-kicker">Console administration</div>
            <h1 class="hero-title">Pilotage des comptes utilisateurs</h1>
            <p class="hero-copy">Retrouve tous les comptes de la plateforme dans une interface plus propre et plus cohérente avec le nouveau style global de Revieweo.</p>
        </div>
    </section>

    <section class="admin-content">
        <div class="container">
            <div class="section-heading">
                <div>
                    <h2 class="section-title">Repertoire des utilisateurs</h2>
                    <p class="section-copy">Consulte les comptes, leur role et leur activite, puis supprime si necessaire.</p>
                </div>
                <div class="users-count"><?= $totalUsers ?> comptes</div>
            </div>

            <div class="users-panel">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Role</th>
                            <th>Reviews</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>#<?= (int) $user['id'] ?></td>
                                <td>
                                    <div class="user-name"><?= htmlspecialchars($user['pseudo'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="user-email"><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></div>
                                </td>
                                <td>
                                    <?php if ((int) $user['role'] === 2): ?>
                                        <span class="role-badge role-admin">Admin</span>
                                    <?php else: ?>
                                        <span class="role-badge role-user">Utilisateur</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= (int) $user['total_reviews'] ?></td>
                                <td>
                                    <?php if ((int) $user['id'] !== (int) $_SESSION['user']['id']): ?>
                                        <a class="btn-delete-user" href="/revieweo/pages/admin_users.php?delete=<?= (int) $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</a>
                                    <?php else: ?>
                                        <span class="self-label">Vous</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>

