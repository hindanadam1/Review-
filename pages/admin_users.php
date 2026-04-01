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

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    if ($deleteId !== (int) $_SESSION['user']['id']) {
        $stmt = $pdo->prepare('DELETE FROM user WHERE id = ?');
        $stmt->execute([$deleteId]);
    }
    header('Location: admin_users.php');
    exit();
}

$stmt = $pdo->query("
    SELECT user.id, user.pseudo, user.email, user.role,
           COUNT(critique.id) AS total_reviews
    FROM user
    LEFT JOIN critique ON critique.id_user = user.id
    GROUP BY user.id, user.pseudo, user.email, user.role
    ORDER BY user.role DESC, user.id DESC
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalUsers = count($users);
$adminCount = 0;
foreach ($users as $user) {
    if ((int) $user['role'] === 2) {
        $adminCount++;
    }
}
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
    background:
        radial-gradient(circle at top left, rgba(255, 33, 33, 0.15), transparent 28%),
        linear-gradient(180deg, #0f0505 0%, #060606 45%, #020202 100%);
    color: #fff;
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
    bottom: -100px;
    right: -30px;
    width: 280px;
    height: 280px;
    background: radial-gradient(circle, rgba(255, 59, 48, 0.45), transparent 70%);
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
.users-panel {
    background: rgba(255,255,255,0.96);
    border-radius: 28px;
    padding: 18px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.28);
}
.users-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px;
}
.users-table thead th {
    color: #7a2222;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1.6px;
    padding: 0 18px 8px;
}
.users-table tbody tr {
    background: linear-gradient(180deg, #fff 0%, #f7f7f7 100%);
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
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
    background: linear-gradient(135deg, #ff3226 0%, #980c08 100%);
    color: #fff;
    padding: 10px 16px;
    border-radius: 12px;
    font-weight: 700;
}
.btn-delete-user:hover {
    color: #fff;
    background: linear-gradient(135deg, #d31d14 0%, #7d0906 100%);
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
}
    </style>
</head>
<body>
    <div class="container admin-shell">
        <div class="admin-topbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <div class="brand-mark">REVIEWEO ADMIN</div>
                <div class="text-light-emphasis">Vue complete des utilisateurs de la plateforme</div>
            </div>
            <div class="admin-nav">
                <a href="/revieweo/pages/admin_reviews.php">Admin Reviews</a>
                <a class="active" href="/revieweo/pages/admin_users.php">Admin Users</a>
                <a href="/revieweo/pages/dashboard.php">Dashboard</a>
                <a href="/revieweo/auth/logout.php">Deconnexion</a>
            </div>
        </div>

        <div class="hero-panel">
            <div class="hero-label">Administration</div>
            <h1 class="hero-title">Tous les utilisateurs, visibles en un instant</h1>
            <p class="hero-copy">Cette interface rassemble les comptes, leur role et leur activite. Tu peux garder un oeil sur la plateforme et supprimer un compte si necessaire.</p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6">
                <div class="stat-card">
                    <h3>Total utilisateurs</h3>
                    <p><?= $totalUsers ?></p>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="stat-card">
                    <h3>Administrateurs</h3>
                    <p><?= $adminCount ?></p>
                </div>
            </div>
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
</body>
</html>
