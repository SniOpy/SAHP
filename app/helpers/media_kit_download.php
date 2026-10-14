<?php

declare(strict_types=1);

/**
 * Espace médias SAHP — téléchargement d'un fichier ou d'une catégorie en ZIP.
 * Appelé par public/index.php pour l'URL /espace-medias/telecharger :
 *   ?id=xxxx            → téléchargement
 *   ?id=xxxx&inline=1   → affichage (images, SVG, PDF)
 *   ?zip=logos          → toute la catégorie en .zip
 */

require_once __DIR__ . '/media_kit.php';

function km_not_found(): void
{
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Fichier introuvable.';
    exit;
}

function km_disposition(string $type, string $name): string
{
    $ascii = preg_replace('/[^A-Za-z0-9._-]/', '_', $name);
    return $type . '; filename="' . $ascii . '"; filename*=UTF-8\'\'' . rawurlencode($name);
}

while (ob_get_level() > 0) {
    ob_end_clean();
}

$isAdmin = km_is_admin();
if (session_status() === PHP_SESSION_ACTIVE) session_write_close();

/* ---------- ZIP d'une catégorie ---------- */
if (isset($_GET['zip'])) {
    $cat = (string) $_GET['zip'];
    $list = array_filter(km_sorted_items(), fn($it) => !empty($it['visible']) && $it['category'] === $cat);
    if (!$list) km_not_found();
    if (!class_exists('ZipArchive')) {
        http_response_code(500);
        exit("Le serveur ne permet pas de créer des archives ZIP.");
    }
    $tmp = tempnam(sys_get_temp_dir(), 'sahpzip');
    $zip = new ZipArchive();
    $zip->open($tmp, ZipArchive::OVERWRITE);
    $used = [];
    foreach ($list as $it) {
        $path = KM_STORAGE . '/' . basename((string) $it['file']);
        if (!is_file($path)) continue;
        $name = km_download_name($it);
        $n = 2;
        while (isset($used[$name])) {
            $name = pathinfo(km_download_name($it), PATHINFO_FILENAME) . '-' . $n++ . '.' . $it['ext'];
        }
        $used[$name] = true;
        $zip->addFile($path, $name);
    }
    $zip->close();
    $zipName = 'sahp-' . km_slug(km_cat_label($cat)) . '.zip';
    header('Content-Type: application/zip');
    header('Content-Disposition: ' . km_disposition('attachment', $zipName));
    header('Content-Length: ' . filesize($tmp));
    header('X-Content-Type-Options: nosniff');
    readfile($tmp);
    @unlink($tmp);
    exit;
}

/* ---------- Fichier unique ---------- */
$id = (string) ($_GET['id'] ?? '');
if (!km_valid_id($id)) km_not_found();

$items = km_items();
$idx = km_find_index($items, $id);
if ($idx === null) km_not_found();
$it = $items[$idx];
if (empty($it['visible']) && !$isAdmin) km_not_found();

$path = KM_STORAGE . '/' . basename((string) $it['file']);
if (!is_file($path)) km_not_found();

$ext = (string) $it['ext'];
$inline = !empty($_GET['inline']) && km_preview_kind($it) !== null;

if (!$inline && !$isAdmin) {
    try {
        km_mutate_items(function (array &$items) use ($id) {
            $i = km_find_index($items, $id);
            if ($i !== null) $items[$i]['downloads'] = (int) ($items[$i]['downloads'] ?? 0) + 1;
        });
    } catch (Throwable $e) {
        // le compteur ne doit jamais bloquer un téléchargement
    }
}

header('Content-Type: ' . km_mime_for($ext));
header('Content-Length: ' . filesize($path));
header('X-Content-Type-Options: nosniff');
header('Content-Disposition: ' . km_disposition($inline ? 'inline' : 'attachment', km_download_name($it)));
if ($ext === 'svg') {
    // Un SVG ne peut exécuter aucun script, même ouvert directement
    header("Content-Security-Policy: default-src 'none'; style-src 'unsafe-inline'; img-src data:; sandbox");
}
if ($inline) {
    header('Cache-Control: ' . (empty($it['visible']) ? 'private, no-store' : 'public, max-age=3600'));
}
readfile($path);
exit;
