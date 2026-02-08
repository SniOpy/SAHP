<?php
declare(strict_types=1);

// Démarrer la session au tout début (avant tout contenu ou header)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Charger les variables d'environnement depuis .env
$envPath = dirname(__DIR__, 2) . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

/*
|--------------------------------------------------------------------------
| ENVIRONNEMENT
|--------------------------------------------------------------------------
| true  = développement (local)
| false = production
*/
define('APP_ENV', true); 

/*
|--------------------------------------------------------------------------
| BASE URL
|--------------------------------------------------------------------------
*/
if (APP_ENV === true) {
    // WAMP / localhost
    define('BASE_URL', '/sahp/public');
} else {
    // PROD (racine du domaine)
    define('BASE_URL', '');
}

/*
|--------------------------------------------------------------------------
| PATHS
|--------------------------------------------------------------------------
*/
define('ROOT_PATH', dirname(__DIR__, 2));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/Views');
