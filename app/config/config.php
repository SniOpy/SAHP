<?php

declare(strict_types=1);

// Ne démarrer la session QUE si nécessaire (formulaires, admin, etc.)
function sahp_needs_session(): bool
{
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

/*
|--------------------------------------------------------------------------
| Cache navigateur des CSS/JS (?v=)
|--------------------------------------------------------------------------
|
| Sur localhost / 127.0.0.1 / [::1], ou SAHP_DISABLE_ASSET_CACHE=1 dans .env : la version dans
| l’URL est recalée à chaque requête (plus de fichier « périmé » en local).
| À la place d’un faux host distant : ajoutez SAHP_DISABLE_ASSET_CACHE=1 dans .env.
| Pour retrouver un cache dur sur localhost : SAHP_FORCE_PRODUCTION_ASSET_CACHE=1
|
*/

$sahpHost = $_SERVER['HTTP_HOST'] ?? '';
$sahpLocalHost = $sahpHost !== ''
    && preg_match('#^(localhost|\\[::1\\]|127\\.0\\.0\\.1)(:\\d+)?$#i', $sahpHost) === 1;
$sahpEnvAssetNoCache = ($_ENV['SAHP_DISABLE_ASSET_CACHE'] ?? '') === '1';
$sahpForceProdAssetCache = ($_ENV['SAHP_FORCE_PRODUCTION_ASSET_CACHE'] ?? '') === '1';
define(
    'SAHP_DISABLE_ASSET_CACHE',
    ! $sahpForceProdAssetCache && ($sahpEnvAssetNoCache || $sahpLocalHost)
);
define(
    'SAHP_ASSET_VERSION',
    SAHP_DISABLE_ASSET_CACHE ? (string) ($_SERVER['REQUEST_TIME'] ?? time()) : '20260517-1'
);

/*
|--------------------------------------------------------------------------
| ENVIRONNEMENT (défini avant session pour cookies sécurisés)
|--------------------------------------------------------------------------
*/

define('APP_ENV', true);

/*
|--------------------------------------------------------------------------
| BASE URL
|--------------------------------------------------------------------------
*/
if (APP_ENV === true) {
    define('BASE_URL', '/sahp/public');
} else {
    define('BASE_URL', '');
}

// Démarrer la session seulement si nécessaire (cookies sécurisés en HTTPS)
if (sahp_needs_session() && session_status() === PHP_SESSION_NONE) {
    if (!APP_ENV && !empty($_SERVER['HTTPS'])) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
    session_start();
}

/*
|--------------------------------------------------------------------------
| PATHS
|--------------------------------------------------------------------------
*/
define('ROOT_PATH', dirname(__DIR__, 2));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/Views');

/*
| .env optionnel : ADMIN_ARTICLE_HTML_USERNAMES
| Pseudos autorisés pour l’onglet « Avancé (HTML) » dans l’édition d’articles admin (séparés par virgules).
*/
