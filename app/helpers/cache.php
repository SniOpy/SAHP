<?php

declare(strict_types=1);

/**
 * Cache HTML full-page
 * - Lecture : si un fichier cache valide existe, on le sert
 * - Écriture : après rendu, on sauvegarde le HTML (via index.php output buffer)
 */

define('CACHE_HTML_DIR', ROOT_PATH . '/data/cache/html');
define('CACHE_HTML_TTL', 3600); // 1 heure en secondes

/** Pages qu'on ne met jamais en cache (formulaires, mentions, etc.) */
const CACHE_EXCLUDED_PAGES = [
    'contact',
    'devis',
    'admin',
    'login',
    'mentions-legales',
    'conditions-generales-prestations-services',
    'politique-confidentialite',
    'plan-site',
];

/**
 * Indique si la requête courante est éligible au cache (GET, page cachable).
 */
function cache_can_use(): bool
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        return false;
    }
    if (!defined('APP_ENV') || APP_ENV === true) {
        return false; // pas de cache en dev
    }
    return true;
}

/**
 * Indique si cette route/request doit être mise en cache.
 */
function cache_should_cache_for_request(string $requestPath): bool
{
    $parts = $requestPath === '' ? [] : explode('/', $requestPath);
    $first = $parts[0] ?? '';
    if (in_array($first, CACHE_EXCLUDED_PAGES, true)) {
        return false;
    }
    return true;
}

/**
 * Génère la clé de cache (nom de fichier sans extension).
 * Ex: '' -> sahp_public, 'a-propos' -> sahp_public_a-propos, 'paroles-de-pro/slug' -> sahp_public_paroles-de-pro_slug
 */
function cache_get_key(string $requestPath): string
{
    $prefix = 'sahp_public';
    if ($requestPath === '') {
        return $prefix;
    }
    $safe = preg_replace('#[^a-z0-9/-]#i', '', str_replace('/', '_', $requestPath));
    return $prefix . '_' . $safe;
}

/**
 * Lit le cache HTML s'il existe et n'est pas expiré.
 * Retourne le contenu ou null.
 */
function cache_get(string $cacheKey): ?string
{
    $dir = CACHE_HTML_DIR;
    if (!is_dir($dir)) {
        return null;
    }
    $file = $dir . '/' . $cacheKey . '.html';
    if (!is_file($file)) {
        return null;
    }
    if (filemtime($file) + CACHE_HTML_TTL < time()) {
        return null;
    }
    $content = file_get_contents($file);
    return $content !== false ? $content : null;
}

/**
 * Enregistre le HTML dans le cache.
 */
function cache_set(string $cacheKey, string $html): void
{
    $dir = CACHE_HTML_DIR;
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $file = $dir . '/' . $cacheKey . '.html';
    file_put_contents($file, $html, LOCK_EX);
}

/**
 * Invalide une entrée de cache (ou tout le cache si $cacheKey est null).
 */
function cache_invalidate(?string $cacheKey = null): void
{
    $dir = CACHE_HTML_DIR;
    if (!is_dir($dir)) {
        return;
    }
    if ($cacheKey === null) {
        $files = glob($dir . '/*.html');
        foreach ($files ?: [] as $f) {
            @unlink($f);
        }
        return;
    }
    $file = $dir . '/' . $cacheKey . '.html';
    if (is_file($file)) {
        @unlink($file);
    }
}
