<?php
session_start();
require_once '../config/db.php';

$authService->requireLogin('../auth/login.php');

$userId = $authService->userId();
$isAdmin = $authService->isAdmin();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $reviewService->delete($id, $userId, $isAdmin);
}

header('Location: ' . ($isAdmin ? 'admin_reviews.php' : 'dashboard.php'));
exit();

