<?php

function sahp_is_cacheable_request(): bool
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') return false;

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

    // ✅ Exclusions (pages dynamiques / sensibles)
    $excludePrefixes = [
        '/devis',
        '/contact',
        '/process',
        '/admin',
        '/login',
        '/logout',
    ];

    foreach ($excludePrefixes as $prefix) {
        if (strpos($uri, $prefix) === 0) return false;
    }

    // ✅ Si session/cookies => pas de cache (ex: admin)
    foreach ($_COOKIE as $k => $v) {
        if (stripos($k, 'PHPSESSID') !== false) return false;
        if (stripos($k, 'auth') !== false) return false;
    }

    return true;
}

function sahp_cache_file_path(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    $uri = rtrim($uri, '/');
    if ($uri === '') $uri = '/';

    // home -> home.html
    $slug = ($uri === '/') ? 'home' : trim(str_replace('/', '_', $uri), '_');

    $cacheDir = __DIR__ . '/../../data/cache/html';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    return $cacheDir . '/' . $slug . '.html';
}

function sahp_try_serve_cache(int $ttlSeconds = 3600): void
{
    if (!sahp_is_cacheable_request()) return;

    $file = sahp_cache_file_path();

    if (file_exists($file)) {
        $age = time() - filemtime($file);

        if ($age < $ttlSeconds) {
            header("X-Cache: HIT");
            header("Cache-Control: public, max-age=300");
            readfile($file);
            exit;
        }
    }

    header("X-Cache: MISS");

    // ✅ Buffer pour enregistrer la sortie HTML à la fin
    ob_start(function ($html) use ($file) {
        if (http_response_code() >= 400) return $html; // pas de cache erreurs

        // petite sécurité : cache uniquement du HTML
        if (stripos($html, '<html') === false) return $html;

        file_put_contents($file, $html);
        return $html;
    });
}
