<?php

declare(strict_types=1);

/**
 * Plan du site au format XML (réponse pour /sitemap.xml via réécriture .htaccess).
 * N’affiche pas d’erreurs PHP dans le corps de la réponse.
 */

while (ob_get_level() > 0) {
    ob_end_clean();
}

ini_set('display_errors', '0');

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/helpers/sitemap_helper.php';

header('Content-Type: application/xml; charset=UTF-8');

$siteOrigin = sitemap_resolve_site_origin();

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach (sitemap_static_pages() as $entry) {
    [$pathSuffix, $lastmod, $changefreq, $priority] = $entry;
    $loc = $pathSuffix === '/' ? $siteOrigin . '/' : sitemap_full_url($siteOrigin, $pathSuffix);
    echo '  <url>' . "\n";
    echo '    <loc>' . sitemap_xml_escape($loc) . '</loc>' . "\n";
    echo '    <lastmod>' . sitemap_xml_escape($lastmod) . '</lastmod>' . "\n";
    echo '    <changefreq>' . sitemap_xml_escape($changefreq) . '</changefreq>' . "\n";
    echo '    <priority>' . sitemap_xml_escape($priority) . '</priority>' . "\n";
    echo '  </url>' . "\n";
}

foreach (sitemap_load_published_articles() as $article) {
    $slug = $article['slug'];
    $lastmod = $article['lastmod'];
    $loc = sitemap_full_url($siteOrigin, '/paroles-de-pro/' . rawurlencode($slug));
    echo '  <url>' . "\n";
    echo '    <loc>' . sitemap_xml_escape($loc) . '</loc>' . "\n";
    echo '    <lastmod>' . sitemap_xml_escape($lastmod) . '</lastmod>' . "\n";
    echo '    <changefreq>monthly</changefreq>' . "\n";
    echo '    <priority>0.7</priority>' . "\n";
    echo '  </url>' . "\n";
}

echo '</urlset>';
