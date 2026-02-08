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
        $mtime = filemtime($file);

        if ($age < $ttlSeconds) {
            // Headers optimisés pour le cache
            header("X-Cache: HIT");
            header("Cache-Control: public, max-age=" . $ttlSeconds . ", stale-while-revalidate=3600");
            header("Expires: " . gmdate("D, d M Y H:i:s", time() + $ttlSeconds) . " GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s", $mtime) . " GMT");
            header("ETag: \"" . md5_file($file) . "\"");
            
            // Vérifier If-None-Match (ETag) pour économiser la bande passante
            if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
                $etag = trim($_SERVER['HTTP_IF_NONE_MATCH'], '"');
                if ($etag === md5_file($file)) {
                    http_response_code(304);
                    exit;
                }
            }
            
            // Vérifier If-Modified-Since
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
                $ifModifiedSince = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
                if ($ifModifiedSince >= $mtime) {
                    http_response_code(304);
                    exit;
                }
            }
            
            // Envoyer le fichier de manière optimisée
            header("Content-Type: text/html; charset=UTF-8");
            header("Content-Length: " . filesize($file));
            
            // Utiliser readfile avec buffer pour de meilleures performances
            $handle = fopen($file, 'rb');
            if ($handle) {
                while (!feof($handle)) {
                    echo fread($handle, 8192); // Buffer de 8KB
                    flush();
                }
                fclose($handle);
            } else {
                readfile($file);
            }
            exit;
        }
    }

    header("X-Cache: MISS");

    // ✅ Buffer pour enregistrer la sortie HTML à la fin
    ob_start(function ($html) use ($file, $ttlSeconds) {
        if (http_response_code() >= 400) return $html; // pas de cache erreurs

        // petite sécurité : cache uniquement du HTML
        if (stripos($html, '<html') === false) return $html;

        // Écrire le fichier de manière atomique pour éviter les corruptions
        $tmpFile = $file . '.tmp';
        if (file_put_contents($tmpFile, $html) !== false) {
            rename($tmpFile, $file);
            // Définir les permissions
            chmod($file, 0644);
        }
        
        return $html;
    }, 8192); // Buffer de 8KB
}
