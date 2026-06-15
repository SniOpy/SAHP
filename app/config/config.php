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

/**
 * Charge le fichier .env (plusieurs emplacements selon déploiement OVH / WAMP).
 */
function sahp_load_dotenv(): void
{
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $loaded = true;

    $projectRoot = dirname(__DIR__, 2);
    $candidates = [
        $projectRoot . DIRECTORY_SEPARATOR . '.env',
        dirname($projectRoot) . DIRECTORY_SEPARATOR . '.env',
        dirname(__DIR__) . DIRECTORY_SEPARATOR . 'env.local.php',
    ];

    $documentRoot = (string) ($_SERVER['DOCUMENT_ROOT'] ?? '');
    if ($documentRoot !== '') {
        $documentRoot = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $documentRoot), DIRECTORY_SEPARATOR);
        $candidates[] = $documentRoot . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env';
        $candidates[] = $documentRoot . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env';
    }

    foreach ($candidates as $envPath) {
        if (! is_readable($envPath)) {
            continue;
        }

        if (str_ends_with(strtolower($envPath), '.php')) {
            /** @noinspection PhpIncludeInspection */
            require $envPath;

            if (! defined('SAHP_DOTENV_LOADED_PATH')) {
                define('SAHP_DOTENV_LOADED_PATH', $envPath);
            }

            return;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            continue;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (strpos($line, '=') === false) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if ($value !== '' && (
                (str_starts_with($value, '"') && str_ends_with($value, '"'))
                || (str_starts_with($value, "'") && str_ends_with($value, "'"))
            )) {
                $value = substr($value, 1, -1);
            }
            $_ENV[$key] = $value;
        }

        if (! defined('SAHP_DOTENV_LOADED_PATH')) {
            define('SAHP_DOTENV_LOADED_PATH', $envPath);
        }

        return;
    }
}

sahp_load_dotenv();

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
|
| APP_ENV = true  → développement local (BASE_URL /sahp/public)
| APP_ENV = false → production (BASE_URL vide, assets à la racine du site)
|
| Production si :
|   - APP_ENV=production dans .env, ou
|   - nom de domaine sahp-idf.fr (hébergement OVH)
| Développement si :
|   - APP_ENV=development dans .env, ou
|   - localhost / 127.0.0.1
|
*/

$sahpHttpHost = preg_replace('/:\d+$/', '', (string) ($_SERVER['HTTP_HOST'] ?? ''));
$sahpEnvMode = strtolower(trim((string) ($_ENV['APP_ENV'] ?? '')));
$sahpIsLocalHost = $sahpHttpHost === ''
    || preg_match('#^(localhost|127\.0\.0\.1|\[::1\])$#i', $sahpHttpHost) === 1;

// Forcer les chemins publics depuis .env (ex. SAHP_BASE_URL= vide en production)
if (array_key_exists('SAHP_BASE_URL', $_ENV)) {
    $sahpBaseUrl = rtrim(trim((string) $_ENV['SAHP_BASE_URL']), '/');
    define('APP_ENV', $sahpBaseUrl !== '');
    define('BASE_URL', $sahpBaseUrl);
} else {
    if ($sahpEnvMode === 'production') {
        $sahpIsProduction = true;
    } elseif ($sahpEnvMode === 'development' || $sahpEnvMode === 'local') {
        $sahpIsProduction = false;
    } elseif (preg_match('#(^|\.)sahp-idf\.fr$#i', $sahpHttpHost) === 1) {
        $sahpIsProduction = true;
    } else {
        $sahpIsProduction = ! $sahpIsLocalHost;
    }

    define('APP_ENV', ! $sahpIsProduction);
    define('BASE_URL', $sahpIsProduction ? '' : '/sahp/public');
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
