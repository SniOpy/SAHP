<?php

declare(strict_types=1);

/**
 * Espace médias SAHP — fonctions communes (page publique, téléchargements, administration).
 *
 * Stockage sans base de données :
 *   data/espace-medias/media.json      classement des fichiers
 *   data/espace-medias/storage/        originaux, servis uniquement par /espace-medias/telecharger
 *   public/uploads/espace-medias/thumbs/  miniatures publiques (PNG/JPG générés par GD)
 */

require_once __DIR__ . '/sitemap_helper.php';

define('KM_ROOT', dirname(__DIR__, 2));
define('KM_DATA', KM_ROOT . '/data/espace-medias');
define('KM_STORAGE', KM_DATA . '/storage');
define('KM_THUMBS', KM_ROOT . '/public/uploads/espace-medias/thumbs');

/* ---------- Configuration ---------- */

function km_cfg(?string $key = null)
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require KM_ROOT . '/app/config/espace-medias.php';
    }
    return $key === null ? $cfg : ($cfg[$key] ?? null);
}

function km_categories(): array
{
    return (array) km_cfg('categories');
}

function km_cat_label(string $key): string
{
    $cats = km_categories();
    return $cats[$key] ?? 'Autres';
}

/* ---------- Utilitaires ---------- */

function km_e($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function km_human_size(int $bytes): string
{
    if ($bytes < 1024) return $bytes . ' o';
    if ($bytes < 1048576) return number_format($bytes / 1024, 0, ',', ' ') . ' Ko';
    return number_format($bytes / 1048576, 1, ',', ' ') . ' Mo';
}

function km_slug(string $s): string
{
    $map = [
        'à'=>'a','â'=>'a','ä'=>'a','á'=>'a','ã'=>'a','å'=>'a','ç'=>'c','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
        'î'=>'i','ï'=>'i','í'=>'i','ì'=>'i','ô'=>'o','ö'=>'o','ó'=>'o','ò'=>'o','õ'=>'o','ù'=>'u','û'=>'u',
        'ü'=>'u','ú'=>'u','ÿ'=>'y','ñ'=>'n','œ'=>'oe','æ'=>'ae','À'=>'a','Â'=>'a','Ä'=>'a','Ç'=>'c','É'=>'e',
        'È'=>'e','Ê'=>'e','Ë'=>'e','Î'=>'i','Ï'=>'i','Ô'=>'o','Ö'=>'o','Ù'=>'u','Û'=>'u','Ü'=>'u','Œ'=>'oe',
    ];
    $s = strtr($s, $map);
    $s = strtolower($s);
    $s = preg_replace('/[^a-z0-9]+/', '-', $s) ?? '';
    $s = trim($s, '-');
    return $s !== '' ? $s : 'fichier';
}

function km_title_from_filename(string $name): string
{
    $base = pathinfo($name, PATHINFO_FILENAME);
    $base = trim(preg_replace('/[_\-]+/', ' ', $base) ?? $base);
    if ($base === '') return 'Sans titre';
    return mb_strtoupper(mb_substr($base, 0, 1)) . mb_substr($base, 1);
}

/* ---------- URLs ---------- */

/** Chemin public (avec BASE_URL) : km_href('espace-medias') → /sahp/public/espace-medias en local, /espace-medias en production. */
function km_href(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** URL absolue (copie de lien, partage). */
function km_absolute_url(string $path = ''): string
{
    return rtrim(sitemap_resolve_site_origin(), '/') . '/' . ltrim($path, '/');
}

function km_download_path(string $id, bool $inline = false): string
{
    return 'espace-medias/telecharger?id=' . rawurlencode($id) . ($inline ? '&inline=1' : '');
}

function km_zip_path(string $category): string
{
    return 'espace-medias/telecharger?zip=' . rawurlencode($category);
}

function km_admin_href(string $query = ''): string
{
    return km_href('admin/espace-medias/') . ($query !== '' ? '?' . $query : '');
}

/* ---------- Stockage JSON ---------- */

function km_ensure_dirs(): void
{
    foreach ([KM_DATA, KM_STORAGE, KM_THUMBS] as $d) {
        if (!is_dir($d)) {
            @mkdir($d, 0755, true);
        }
    }
}

function km_read_json(string $name, $default)
{
    $path = KM_DATA . '/' . $name;
    if (!is_file($path)) return $default;
    $raw = @file_get_contents($path);
    $data = json_decode((string) $raw, true);
    return is_array($data) ? $data : $default;
}

function km_write_json(string $name, $data): void
{
    km_ensure_dirs();
    $path = KM_DATA . '/' . $name;
    $tmp = $path . '.' . bin2hex(random_bytes(4)) . '.tmp';
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false || file_put_contents($tmp, $json, LOCK_EX) === false) {
        throw new RuntimeException("Impossible d'écrire dans le dossier data/espace-medias/ (vérifiez les droits d'écriture).");
    }
    if (!@rename($tmp, $path)) {
        @unlink($tmp);
        throw new RuntimeException("Impossible d'enregistrer $name.");
    }
}

/** Exécute $fn(&$items) sous verrou exclusif puis enregistre. */
function km_mutate_items(callable $fn)
{
    km_ensure_dirs();
    $lock = fopen(KM_DATA . '/.lock', 'c');
    if ($lock) flock($lock, LOCK_EX);
    try {
        $items = km_items();
        $result = $fn($items);
        km_write_json('media.json', ['items' => array_values($items)]);
        return $result;
    } finally {
        if ($lock) {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}

function km_items(): array
{
    $d = km_read_json('media.json', ['items' => []]);
    return isset($d['items']) && is_array($d['items']) ? $d['items'] : [];
}

function km_sorted_items(): array
{
    $items = km_items();
    $catOrder = array_flip(array_keys(km_categories()));
    usort($items, function ($a, $b) use ($catOrder) {
        $ca = $catOrder[$a['category']] ?? 999;
        $cb = $catOrder[$b['category']] ?? 999;
        if ($ca !== $cb) return $ca <=> $cb;
        $oa = (int) ($a['order'] ?? 0);
        $ob = (int) ($b['order'] ?? 0);
        if ($oa !== $ob) return $oa <=> $ob;
        return strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? ''));
    });
    return $items;
}

function km_find_index(array $items, string $id): ?int
{
    foreach ($items as $i => $it) {
        if (($it['id'] ?? '') === $id) return $i;
    }
    return null;
}

function km_valid_id(string $id): bool
{
    return (bool) preg_match('/^[a-f0-9]{16}$/', $id);
}

/* ---------- Types de fichiers ---------- */

const KM_RASTER = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

function km_types(): array
{
    return [
        'jpg'  => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png'  => ['image/png'],
        'webp' => ['image/webp'],
        'gif'  => ['image/gif'],
        'svg'  => ['image/svg+xml', 'text/xml', 'application/xml', 'text/plain', 'text/html'],
        'pdf'  => ['application/pdf'],
        'ai'   => ['application/pdf', 'application/postscript', 'application/illustrator'],
        'eps'  => ['application/postscript', 'image/x-eps', 'application/eps', 'application/octet-stream'],
        'zip'  => ['application/zip', 'application/x-zip-compressed'],
        'mp4'  => ['video/mp4'],
    ];
}

function km_mime_for(string $ext): string
{
    $map = [
        'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp',
        'gif' => 'image/gif', 'svg' => 'image/svg+xml', 'pdf' => 'application/pdf',
        'ai' => 'application/postscript', 'eps' => 'application/postscript',
        'zip' => 'application/zip', 'mp4' => 'video/mp4',
    ];
    return $map[$ext] ?? 'application/octet-stream';
}

function km_accept_attr(): string
{
    return implode(',', array_map(fn($e) => '.' . $e, array_keys(km_types())));
}

function km_upload_error(int $code): string
{
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE: return 'fichier trop lourd pour le serveur';
        case UPLOAD_ERR_PARTIAL:   return 'envoi interrompu, réessayez';
        case UPLOAD_ERR_NO_FILE:   return 'aucun fichier';
        case UPLOAD_ERR_NO_TMP_DIR:
        case UPLOAD_ERR_CANT_WRITE: return 'le serveur ne peut pas écrire le fichier';
        default: return "erreur d'envoi ($code)";
    }
}

/** Réorganise $_FILES['x'] (multiple) en liste de fichiers. */
function km_normalize_files(?array $f): array
{
    if (!$f || !isset($f['name'])) return [];
    if (!is_array($f['name'])) return [$f];
    $out = [];
    foreach ($f['name'] as $i => $name) {
        $out[] = [
            'name' => $name,
            'type' => $f['type'][$i] ?? '',
            'tmp_name' => $f['tmp_name'][$i] ?? '',
            'error' => $f['error'][$i] ?? UPLOAD_ERR_NO_FILE,
            'size' => $f['size'][$i] ?? 0,
        ];
    }
    return $out;
}

/**
 * Vérifie un fichier envoyé. Retourne l'extension normalisée.
 * @param array|null $onlyExt limite aux extensions données (ex. images d'aperçu)
 */
function km_validate_upload(array $f, ?array $onlyExt = null): string
{
    $name = (string) ($f['name'] ?? '');
    if (($f['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException("$name : " . km_upload_error((int) $f['error']));
    }
    if (!is_uploaded_file($f['tmp_name'])) {
        throw new RuntimeException("$name : envoi invalide");
    }
    $max = (int) km_cfg('max_upload_mb') * 1048576;
    if ($f['size'] > $max) {
        throw new RuntimeException("$name : dépasse " . km_cfg('max_upload_mb') . ' Mo');
    }
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if ($ext === 'jpeg') $ext = 'jpg';
    $types = km_types();
    if (!isset($types[$ext]) || ($onlyExt !== null && !in_array($ext, $onlyExt, true))) {
        throw new RuntimeException("$name : format non accepté");
    }
    if (function_exists('finfo_open')) {
        $fi = finfo_open(FILEINFO_MIME_TYPE);
        $mime = (string) finfo_file($fi, $f['tmp_name']);
        finfo_close($fi);
        if (!in_array($mime, $types[$ext], true)) {
            throw new RuntimeException("$name : le contenu ne correspond pas à l'extension .$ext");
        }
    }
    if ($ext === 'svg') {
        $svg = (string) file_get_contents($f['tmp_name']);
        if (stripos($svg, '<svg') === false
            || preg_match('/<script|\son[a-z]+\s*=|javascript:|<foreignObject|<iframe|<embed|<object/i', $svg)) {
            throw new RuntimeException("$name : SVG refusé (contenu actif détecté)");
        }
    }
    if (in_array($ext, KM_RASTER, true) && @getimagesize($f['tmp_name']) === false) {
        throw new RuntimeException("$name : image illisible");
    }
    return $ext;
}

function km_new_id(): string
{
    return bin2hex(random_bytes(8));
}

/* ---------- Miniatures ---------- */

/** Crée une miniature dans public/uploads/espace-medias/thumbs/. Retourne le nom du fichier ou null. */
function km_make_thumb(string $src, string $ext, string $baseName, int $max = 800): ?string
{
    km_ensure_dirs();
    if (in_array($ext, KM_RASTER, true) && function_exists('imagecreatetruecolor')) {
        $info = @getimagesize($src);
        if (!$info) return null;
        [$w, $h] = $info;
        if ($w < 1 || $h < 1 || $w * $h > 60000000) return null;

        switch ($ext) {
            case 'jpg':
            case 'jpeg': $im = @imagecreatefromjpeg($src); break;
            case 'png':  $im = @imagecreatefrompng($src); break;
            case 'gif':  $im = @imagecreatefromgif($src); break;
            case 'webp': $im = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($src) : false; break;
            default: $im = false;
        }
        if (!$im) return null;

        // Photos de téléphone : respecter l'orientation EXIF
        if ($ext === 'jpg' && function_exists('exif_read_data')) {
            $exif = @exif_read_data($src);
            $o = (int) ($exif['Orientation'] ?? 1);
            $angle = [3 => 180, 6 => -90, 8 => 90][$o] ?? 0;
            if ($angle) {
                $rot = imagerotate($im, $angle, 0);
                if ($rot) {
                    imagedestroy($im);
                    $im = $rot;
                    $w = imagesx($im);
                    $h = imagesy($im);
                }
            }
        }

        $scale = min(1, $max / max($w, $h));
        $nw = max(1, (int) round($w * $scale));
        $nh = max(1, (int) round($h * $scale));
        $dst = imagecreatetruecolor($nw, $nh);
        $alpha = $ext !== 'jpg' && $ext !== 'jpeg';
        if ($alpha) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        }
        imagecopyresampled($dst, $im, 0, 0, 0, 0, $nw, $nh, $w, $h);
        $out = $baseName . ($alpha ? '.png' : '.jpg');
        $ok = $alpha ? imagepng($dst, KM_THUMBS . '/' . $out, 7) : imagejpeg($dst, KM_THUMBS . '/' . $out, 84);
        imagedestroy($im);
        imagedestroy($dst);
        return $ok ? $out : null;
    }

    // PDF / AI : première page, seulement si l'hébergeur propose Imagick
    if (in_array($ext, ['pdf', 'ai'], true) && class_exists('Imagick')) {
        try {
            $im = new Imagick();
            $im->setResolution(120, 120);
            $im->readImage($src . '[0]');
            $im->setImageBackgroundColor('white');
            $im = $im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
            $im->thumbnailImage($max, $max, true);
            $im->setImageFormat('jpeg');
            $out = $baseName . '.jpg';
            $im->writeImage(KM_THUMBS . '/' . $out);
            $im->clear();
            return $out;
        } catch (Throwable $e) {
            return null;
        }
    }
    return null;
}

function km_image_size(string $path, string $ext): array
{
    if (in_array($ext, KM_RASTER, true)) {
        $i = @getimagesize($path);
        if ($i) return [(int) $i[0], (int) $i[1]];
    }
    return [null, null];
}

function km_delete_thumb(?string $thumb): void
{
    if ($thumb && preg_match('/^[a-f0-9\-p]+\.(png|jpg)$/', $thumb)) {
        @unlink(KM_THUMBS . '/' . $thumb);
    }
}

/* ---------- Affichage ---------- */

/** URL publique de la miniature (avec BASE_URL), ou null. */
function km_thumb_url(array $it): ?string
{
    if (!empty($it['preview'])) return km_href('uploads/espace-medias/thumbs/' . rawurlencode($it['preview']));
    if (!empty($it['thumb'])) return km_href('uploads/espace-medias/thumbs/' . rawurlencode($it['thumb']));
    if (($it['ext'] ?? '') === 'svg') return km_href(km_download_path((string) $it['id'], true));
    return null;
}

function km_preview_kind(array $it): ?string
{
    $ext = $it['ext'] ?? '';
    if (in_array($ext, KM_RASTER, true) || $ext === 'svg') return 'image';
    if ($ext === 'pdf') return 'pdf';
    return null;
}

function km_meta(array $it): string
{
    $parts = [strtoupper($it['ext'] ?? '')];
    $parts[] = km_human_size((int) ($it['size'] ?? 0));
    if (!empty($it['width']) && !empty($it['height'])) {
        $parts[] = $it['width'] . ' × ' . $it['height'] . ' px';
    }
    return implode(' · ', $parts);
}

function km_download_name(array $it): string
{
    $slug = km_slug((string) ($it['title'] ?? 'fichier'));
    if (strpos($slug, 'sahp') === false) $slug = 'sahp-' . $slug;
    return $slug . '.' . ($it['ext'] ?? 'bin');
}

function km_file_icon(string $ext): string
{
    $label = km_e(strtoupper($ext));
    return '<svg class="mk-file-icon" viewBox="0 0 64 80" aria-hidden="true">'
        . '<path d="M8 2h34l20 20v52a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V6a4 4 0 0 1 4-4z" fill="#fff" stroke="currentColor" stroke-width="3"/>'
        . '<path d="M42 2v16a4 4 0 0 0 4 4h16" fill="none" stroke="currentColor" stroke-width="3"/>'
        . '<rect x="0" y="44" width="52" height="20" rx="4" fill="currentColor"/>'
        . '<text x="26" y="58.5" text-anchor="middle" font-family="Roboto,Arial,sans-serif" font-size="12" font-weight="700" fill="#fff">' . $label . '</text>'
        . '</svg>';
}

function km_icon(string $name): string
{
    $icons = [
        'download' => '<path d="M12 3v12m0 0-5-5m5 5 5-5M4 21h16" />',
        'eye'      => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
        'link'     => '<path d="M10 14a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1"/><path d="M14 10a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
        'zip'      => '<path d="M4 7h16v13H4z"/><path d="M4 7l2-4h12l2 4"/><path d="M12 11v6m0 0-3-3m3 3 3-3"/>',
        'edit'     => '<path d="M4 20h4L19 9l-4-4L4 16v4z"/>',
        'trash'    => '<path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13"/>',
        'back'     => '<path d="M15 18l-6-6 6-6"/>',
    ];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
        . ($icons[$name] ?? '') . '</svg>';
}

/* ---------- Session admin ---------- */

/**
 * Vrai si un administrateur du panneau est connecté.
 * Ne crée jamais de session pour un simple visiteur : la session n'est lue que si son cookie existe déjà.
 */
function km_is_admin(): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        if (!isset($_COOKIE[session_name()])) return false;
        @session_start(['read_and_close' => true]);
    }
    // Mêmes clés que ADMIN_SESSION_USER_ID_KEY / ADMIN_SESSION_USERNAME_KEY (public/admin/includes/auth.php)
    return isset($_SESSION['admin_user_id'], $_SESSION['admin_username']);
}

function km_flash(string $type, string $msg): void
{
    $_SESSION['km_flash'][] = [$type, $msg];
}

function km_take_flash(): array
{
    $f = $_SESSION['km_flash'] ?? [];
    unset($_SESSION['km_flash']);
    return $f;
}
