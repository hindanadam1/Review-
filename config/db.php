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

$databaseConfig = [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'dbname' => getenv('DB_NAME') ?: 'revieweo',
    'charset' => getenv('DB_CHARSET') ?: 'utf8',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: 'hind',
];

try {
    $database = Database::getInstance($databaseConfig);
    $pdo = $database->getConnection();
    $authService = new AuthService($pdo);
    $reviewService = new ReviewService($pdo);
    $likeService = new LikeService($pdo);
    $userService = new UserService($pdo);
    $categoryService = new CategoryService($pdo);
} catch (\PDOException $exception) {
    $pdo = null;
    $authService = null;
    $reviewService = null;
    $likeService = null;
    $userService = null;
    $categoryService = new class {
        public function getAll(): array
        {
            return [
                ['nom' => 'Drama'],
                ['nom' => 'Comedy'],
                ['nom' => 'Action'],
                ['nom' => 'Horror'],
                ['nom' => 'Sci-Fi'],
                ['nom' => 'Western'],
                ['nom' => 'Romance'],
                ['nom' => 'Thriller'],
                ['nom' => 'Fantasy'],
                ['nom' => 'Apocalypse'],
                ['nom' => 'Martial Arts'],
                ['nom' => 'Sports'],
            ];
        }
    };
}

$newsletterService = new NewsletterService();
