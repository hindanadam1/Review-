<?php
$pdo = new PDO("mysql:host=localhost;dbname=revieweo;charset=utf8", "root", "hind");

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>