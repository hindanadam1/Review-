<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!$authService->isLoggedIn()) {
    echo json_encode(['error' => 'not_logged']);
    exit();
}

$userId = $authService->userId();
$critiqueId = isset($_POST['id_critique']) ? (int) $_POST['id_critique'] : 0;

if ($critiqueId <= 0) {
    echo json_encode(['error' => 'invalid_critique']);
    exit();
}

echo json_encode($likeService->toggle($userId, $critiqueId));

