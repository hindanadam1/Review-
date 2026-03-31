<?php
session_start();
require_once '../config/db.php';

// 1. Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role'])) {
	echo '<div style="color:red;text-align:center;margin-top:40px;">Accès refusé : vous devez être connecté.</div>';
	exit();
}

// 2. Vérifier que l'utilisateur est admin (role = 2)
if ($_SESSION['user']['role'] != 2) {
	echo '<div style="color:red;text-align:center;margin-top:40px;">Accès refusé : réservé aux administrateurs.</div>';
	exit();
}

// 6. Suppression utilisateur
if (isset($_GET['delete'])) {
	$delete_id = intval($_GET['delete']);
	// On ne peut pas supprimer son propre compte admin
	if ($delete_id != $_SESSION['user']['id']) {
		$stmt = $pdo->prepare('DELETE FROM user WHERE id = ?');
		$stmt->execute([$delete_id]);
	}
	header('Location: admin_users.php');
	exit();
}

// 3. Récupérer tous les utilisateurs
$stmt = $pdo->query('SELECT id, pseudo, email, role FROM user');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Admin - Utilisateurs</title>
	<link rel="stylesheet" href="../style.css">
	<style>
		.admin-table { width: 60%; margin: 40px auto; border-collapse: collapse; background: #181818; color: #fff; }
		.admin-table th, .admin-table td { padding: 14px 10px; border-bottom: 1px solid #333; text-align: left; }
		.admin-table th { background: #900; color: #fff; }
		.admin-table tr:last-child td { border-bottom: none; }
		.admin-btn { background: #ff2222; color: #fff; border: none; padding: 7px 18px; border-radius: 6px; cursor: pointer; transition: background 0.2s; }
		.admin-btn:hover { background: #900; }
		.admin-title { text-align:center; color:#ff2222; margin-top:40px; font-size:2em; }
	</style>
</head>
<body style="background:#111;min-height:100vh;">
	<div class="admin-title">Gestion des utilisateurs</div>
	<table class="admin-table">
		<tr>
			<th>ID</th>
			<th>Pseudo</th>
			<th>Email</th>
			<th>Rôle</th>
			<th>Action</th>
		</tr>
		<?php foreach ($users as $user): ?>
		<tr>
			<td><?= $user['id'] ?></td>
			<td><?= htmlspecialchars($user['pseudo']) ?></td>
			<td><?= htmlspecialchars($user['email']) ?></td>
			<td><?= $user['role'] == 2 ? 'Admin' : 'Utilisateur' ?></td>
			<td>
				<?php if ($user['id'] != $_SESSION['user']['id']): ?>
					<a href="?delete=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?');">
						<button class="admin-btn">Supprimer</button>
					</a>
				<?php else: ?>
					<span style="color:#888;">(vous)</span>
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</body>
</html>
