<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $newsletterService->subscribe($_POST['email'] ?? null);
    $_SESSION['newsletter_message'] = $result['message'];
}

$redirect = $_SERVER['HTTP_REFERER'] ?? '/revieweo/pages/index.php';
header('Location: ' . $redirect);
exit();

