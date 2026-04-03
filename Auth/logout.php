<?php
session_start();
require_once '../config/db.php';

// Déconnecte l'utilisateur en supprimant sa session.
$authService->logout('login.php');
