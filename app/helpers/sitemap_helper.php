<?php

declare(strict_types=1);

/**
 * SEO — origine HTTPS du site pour les URLs absolues dans le sitemap.
 *
 * En production : définir SAHP_SITE_URL=https://sahp-idf.fr dans .env (sans slash final).
 * Sinon : construit automatiquement à partir du host + BASE_URL (ex. localhost / sous-dossier).
 */
function sitemap_resolve_site_origin(): string
{
    $fromEnv = trim((string) ($_ENV['SAHP_SITE_URL'] ?? ''));
    if ($fromEnv !== '') {
        return rtrim($fromEnv, '/');
    }

    $https = ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $scheme = $https ? 'https' : 'http';
    $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
    $prefix = defined('BASE_URL') && BASE_URL !== ''
        ? rtrim(str_replace('\\', '/', (string) BASE_URL), '/')
        : '';

    return $scheme . '://' . $host . $prefix;
}

function sitemap_xml_escape(string $value): string
{
    return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * @return array{0: string, 1: string, 2: string, 3: string} [path, lastmod Y-m-d, changefreq, priority]
 */
function sitemap_static_pages(): array
{
    // Doit rester aligné sur $routes de public/index.php (clé = segment d’URL, sans slash).
    $paths = [
        '',
        'a-propos',
        'mentions-legales',
        'conditions-generales-prestations-services',
        'politique-confidentialite',
        'gestion-cookies',
        'plan-site',
        'curage',
        'pompage',
        'inspection',
        'debouchage',
        'maintenance-pro',
        'urgence',
        'paroles-de-pro',
        'contact',
        'devis',
        'tarifs',
    ];

    $today = gmdate('Y-m-d');
    $out = [];

    foreach ($paths as $path) {
        if ($path === '') {
            $out[] = ['/', $today, 'weekly', '1.0'];
        } elseif (in_array($path, ['contact', 'devis', 'paroles-de-pro', 'urgence'], true)) {
            $out[] = ['/' . $path, $today, 'weekly', '0.9'];
        } elseif (in_array($path, ['debouchage', 'curage', 'inspection', 'pompage', 'maintenance-pro'], true)) {
            $out[] = ['/' . $path, $today, 'monthly', '0.85'];
        } else {
            $out[] = ['/' . $path, $today, 'monthly', '0.6'];
        }
    }

    return $out;
}

/**
 * Date W3C pour lastmod (jour seulement), à partir des champs MySQL.
 */
function sitemap_lastmod_from_row(array $row): string
{
    $candidates = [
        $row['updated_at'] ?? null,
        $row['published_at'] ?? null,
        $row['created_at'] ?? null,
    ];

    $bestTs = 0;
    foreach ($candidates as $raw) {
        if (! is_string($raw) || $raw === '') {
            continue;
        }
        $ts = strtotime($raw);
        if ($ts !== false && $ts > $bestTs) {
            $bestTs = $ts;
        }
    }

    return $bestTs > 0 ? gmdate('Y-m-d', $bestTs) : gmdate('Y-m-d');
}

/**
 * Articles publiés uniquement (is_published = 1), avec slug non vide.
 *
 * @return list<array{slug: string, lastmod: string}>
 */
function sitemap_load_published_articles(): array
{
    require_once __DIR__ . '/database.php';

    try {
        $pdo = getAppDatabaseConnection();
    } catch (Throwable $e) {
        return [];
    }

    try {
        $statement = $pdo->prepare(
            'SELECT slug, published_at, created_at, updated_at
             FROM articles
             WHERE is_published = 1
               AND CHAR_LENGTH(TRIM(slug)) > 0'
        );
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        return [];
    }

    $list = [];
    foreach ($rows as $row) {
        $slug = trim((string) ($row['slug'] ?? ''));
        if ($slug === '' || ! preg_match('#^[a-zA-Z0-9-]+$#', $slug)) {
            continue;
        }
        $list[] = [
            'slug' => $slug,
            'lastmod' => sitemap_lastmod_from_row($row),
        ];
    }

    return $list;
}

/**
 * Compose une URL publique pour le sitemap à partir du chemin (« /contact » ou « / » ).
 */
function sitemap_full_url(string $siteOrigin, string $path): string
{
    $path = $path === '' || $path === '/' ? '/' : '/' . ltrim($path, '/');

    return $siteOrigin . $path;
}
