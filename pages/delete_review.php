<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user']['id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$userId = (int) $_SESSION['user']['id'];
$isAdmin = isset($_SESSION['user']['role']) && (int) $_SESSION['user']['role'] === 2;
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $deleteQuery = $isAdmin
        ? "DELETE FROM critique WHERE id = ?"
        : "DELETE FROM critique WHERE id = ? AND id_user = ?";
    $stmt = $pdo->prepare($deleteQuery);
    $stmt->execute($isAdmin ? [$id] : [$id, $userId]);
}

header('Location: ' . ($isAdmin ? 'admin_reviews.php' : 'dashboard.php'));
exit();
