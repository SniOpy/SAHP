<?php
declare(strict_types=1);

// Ne démarrer la session QUE si nécessaire (formulaires, admin, etc.)
// Les pages statiques en cache n'ont pas besoin de session
function sahp_needs_session(): bool {
    // Si POST, probablement un formulaire - besoin de session pour CSRF
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return true;
    }
    
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
    // Nettoyer l'URI (enlever /sahp/public si présent)
    $uri = str_replace(['/sahp', '/public'], '', $uri);
    $uri = trim($uri, '/');
    
    // Pages qui nécessitent une session
    $needsSession = ['contact', 'devis', 'admin', 'login'];
    foreach ($needsSession as $path) {
        if ($uri === $path || strpos($uri, $path . '/') === 0) {
            return true;
        }
    }
    
    return false;
}

// Charger les variables d'environnement depuis .env (une seule fois)
if (!isset($_ENV['SMTP_HOST'])) {
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
}

// Démarrer la session seulement si nécessaire
if (sahp_needs_session() && session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| ENVIRONNEMENT
|--------------------------------------------------------------------------
| Détection automatique de l'environnement
| true  = développement (local)
| false = production
*/
// Détection automatique : si localhost ou IP locale = dev, sinon = prod
$isLocal = (
    $_SERVER['HTTP_HOST'] === 'localhost' ||
    $_SERVER['HTTP_HOST'] === '127.0.0.1' ||
    strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
    strpos($_SERVER['HTTP_HOST'], '.local') !== false ||
    strpos($_SERVER['HTTP_HOST'], '192.168.') !== false ||
    strpos($_SERVER['HTTP_HOST'], '10.') !== false
);

define('APP_ENV', $isLocal); 

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
