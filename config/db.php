<?php
spl_autoload_register(static function (string $className): void {
    $prefix = 'App\\';
    $baseDirectory = __DIR__ . '/../app/';

    if (strncmp($prefix, $className, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($className, strlen($prefix));
    $file = $baseDirectory . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Core\Database;
use App\Services\AuthService;
use App\Services\CategoryService;
use App\Services\LikeService;
use App\Services\NewsletterService;
use App\Services\ReviewService;
use App\Services\UserService;

$database = Database::getInstance([
    'host' => 'localhost',
    'dbname' => 'revieweo',
    'charset' => 'utf8',
    'username' => 'root',
    'password' => 'hind',
]);

$pdo = $database->getConnection();
$authService = new AuthService($pdo);
$reviewService = new ReviewService($pdo);
$likeService = new LikeService($pdo);
$userService = new UserService($pdo);
$categoryService = new CategoryService($pdo);
$newsletterService = new NewsletterService();
