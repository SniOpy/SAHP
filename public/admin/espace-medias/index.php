<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_csrf.php';
require_once APP_PATH . '/helpers/media_kit.php';

requireAdminLogin();
km_ensure_dirs();
header('X-Frame-Options: DENY');
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex, nofollow');

function km_redirect(string $query = ''): void
{
    header('Location: ' . km_admin_href($query));
    exit;
}

function km_csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . km_e(ensureAdminCsrfToken()) . '">';
}

$action = (string) ($_POST['action'] ?? '');

// Envoi plus lourd que la limite du serveur : PHP vide $_POST et $_FILES
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$_POST && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    km_flash('err', "L'envoi dépasse la limite du serveur (" . ini_get('post_max_size') . '). Envoyez moins de fichiers à la fois ou des fichiers plus légers.');
    km_redirect();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !verifyAdminCsrfTokenFromPost()) {
    km_flash('err', 'Session expirée, veuillez réessayer.');
    km_redirect();
}

/* =========================================================
   Actions
   ========================================================= */
$cats = km_categories();

function km_post_cat(): string
{
    $c = (string) ($_POST['category'] ?? '');
    return isset(km_categories()[$c]) ? $c : (string) array_key_last(km_categories());
}

function km_post_text(string $key, int $max): string
{
    return mb_substr(trim((string) ($_POST[$key] ?? '')), 0, $max);
}

/** Enregistre une image d'aperçu personnalisée (pour PDF, AI, EPS, ZIP…). */
function km_save_preview(array $f, string $id): ?string
{
    $ext = km_validate_upload($f, ['jpg', 'png', 'webp']);
    return km_make_thumb($f['tmp_name'], $ext, $id . '-p', 900);
}

try {
    switch ($action) {
        case 'upload':
            $files = array_filter(km_normalize_files($_FILES['files'] ?? null), fn($f) => ($f['error'] ?? 4) !== UPLOAD_ERR_NO_FILE);
            if (!$files) {
                km_flash('err', 'Choisissez au moins un fichier.');
                break;
            }
            $category = km_post_cat();
            $title = km_post_text('title', 120);
            $desc = km_post_text('description', 600);
            $visible = !empty($_POST['visible']);
            $added = [];
            $errors = [];
            foreach ($files as $f) {
                try {
                    $ext = km_validate_upload($f);
                    $id = km_new_id();
                    $stored = $id . '.' . $ext;
                    if (!move_uploaded_file($f['tmp_name'], KM_STORAGE . '/' . $stored)) {
                        throw new RuntimeException($f['name'] . ' : impossible de copier le fichier sur le serveur');
                    }
                    @chmod(KM_STORAGE . '/' . $stored, 0644);
                    [$w, $h] = km_image_size(KM_STORAGE . '/' . $stored, $ext);
                    $added[] = [
                        'id' => $id,
                        'title' => ($title !== '' && count($files) === 1) ? $title : km_title_from_filename($f['name']),
                        'description' => $desc,
                        'category' => $category,
                        'file' => $stored,
                        'original_name' => mb_substr((string) $f['name'], 0, 200),
                        'ext' => $ext,
                        'size' => (int) filesize(KM_STORAGE . '/' . $stored),
                        'width' => $w,
                        'height' => $h,
                        'thumb' => km_make_thumb(KM_STORAGE . '/' . $stored, $ext, $id),
                        'preview' => null,
                        'visible' => $visible,
                        'order' => 0,
                        'downloads' => 0,
                        'created_at' => date('c'),
                        'updated_at' => date('c'),
                    ];
                } catch (Throwable $e) {
                    $errors[] = $e->getMessage();
                }
            }
            if ($added) {
                km_mutate_items(function (array &$items) use ($added) {
                    foreach ($added as $a) $items[] = $a;
                });
                km_flash('ok', count($added) . ' fichier' . (count($added) > 1 ? 's ajoutés' : ' ajouté') . ' dans « ' . $cats[$category] . ' ».');
            }
            foreach ($errors as $e) km_flash('err', $e);
            break;

        case 'update':
            $id = (string) ($_POST['id'] ?? '');
            if (!km_valid_id($id)) break;
            $newFile = $_FILES['file'] ?? null;
            $newPreview = $_FILES['preview'] ?? null;
            $replace = null;
            if ($newFile && ($newFile['error'] ?? 4) !== UPLOAD_ERR_NO_FILE) {
                $ext = km_validate_upload($newFile);
                $tmpName = 'tmp-' . km_new_id() . '.' . $ext;
                if (!move_uploaded_file($newFile['tmp_name'], KM_STORAGE . '/' . $tmpName)) {
                    throw new RuntimeException('Impossible de remplacer le fichier.');
                }
                $replace = ['ext' => $ext, 'tmp' => $tmpName, 'name' => $newFile['name']];
            }
            $previewName = null;
            if ($newPreview && ($newPreview['error'] ?? 4) !== UPLOAD_ERR_NO_FILE) {
                $previewName = km_save_preview($newPreview, $id);
                if (!$previewName) throw new RuntimeException("Impossible de créer l'image d'aperçu.");
            }
            $data = [
                'title' => km_post_text('title', 120) ?: 'Sans titre',
                'description' => km_post_text('description', 600),
                'category' => km_post_cat(),
                'visible' => !empty($_POST['visible']),
                'order' => max(-999, min(999, (int) ($_POST['order'] ?? 0))),
            ];
            $removePreview = !empty($_POST['remove_preview']);
            $ok = km_mutate_items(function (array &$items) use ($id, $data, $replace, $previewName, $removePreview) {
                $i = km_find_index($items, $id);
                if ($i === null) return false;
                $it = array_merge($items[$i], $data);
                if ($replace) {
                    @unlink(KM_STORAGE . '/' . basename((string) $it['file']));
                    km_delete_thumb($it['thumb'] ?? null);
                    $stored = $id . '.' . $replace['ext'];
                    rename(KM_STORAGE . '/' . $replace['tmp'], KM_STORAGE . '/' . $stored);
                    @chmod(KM_STORAGE . '/' . $stored, 0644);
                    [$w, $h] = km_image_size(KM_STORAGE . '/' . $stored, $replace['ext']);
                    $it['file'] = $stored;
                    $it['ext'] = $replace['ext'];
                    $it['original_name'] = mb_substr((string) $replace['name'], 0, 200);
                    $it['size'] = (int) filesize(KM_STORAGE . '/' . $stored);
                    $it['width'] = $w;
                    $it['height'] = $h;
                    $it['thumb'] = km_make_thumb(KM_STORAGE . '/' . $stored, $replace['ext'], $id);
                }
                if ($previewName) {
                    $it['preview'] = $previewName;
                } elseif ($removePreview && !empty($it['preview'])) {
                    km_delete_thumb($it['preview']);
                    $it['preview'] = null;
                }
                $it['updated_at'] = date('c');
                $items[$i] = $it;
                return true;
            });
            km_flash($ok ? 'ok' : 'err', $ok ? 'Modifications enregistrées.' : 'Fichier introuvable.');
            break;

        case 'toggle':
            $id = (string) ($_POST['id'] ?? '');
            km_mutate_items(function (array &$items) use ($id) {
                $i = km_find_index($items, $id);
                if ($i !== null) $items[$i]['visible'] = empty($items[$i]['visible']);
            });
            break;

        case 'delete':
            $id = (string) ($_POST['id'] ?? '');
            $title = km_mutate_items(function (array &$items) use ($id) {
                $i = km_find_index($items, $id);
                if ($i === null) return null;
                $it = $items[$i];
                @unlink(KM_STORAGE . '/' . basename((string) $it['file']));
                km_delete_thumb($it['thumb'] ?? null);
                km_delete_thumb($it['preview'] ?? null);
                array_splice($items, $i, 1);
                return $it['title'];
            });
            if ($title !== null) km_flash('ok', '« ' . $title . ' » a été supprimé.');
            break;
    }
} catch (Throwable $e) {
    km_flash('err', $e->getMessage());
}

if ($action !== '') {
    $back = (string) ($_POST['back'] ?? '');
    $postedId = (string) ($_POST['id'] ?? '');
    km_redirect($back === 'edit' && $action === 'update' && km_valid_id($postedId) ? 'edit=' . $postedId : '');
}

/* =========================================================
   Affichage
   ========================================================= */
$all = km_sorted_items();
$flash = km_take_flash();

function km_mini(array $it): string
{
    $t = km_thumb_url($it);
    return $t ? '<img src="' . km_e($t) . '" alt="" loading="lazy">' : km_file_icon($it['ext']);
}

function km_cat_select(string $current): string
{
    $h = '<select name="category" id="category" required>';
    foreach (km_categories() as $k => $l) {
        $h .= '<option value="' . km_e($k) . '"' . ($k === $current ? ' selected' : '') . '>' . km_e($l) . '</option>';
    }
    return $h . '</select>';
}

function km_admin_head(string $title): void
{
    ?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= km_e($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=<?= SAHP_ASSET_VERSION ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-auth.css?v=<?= SAHP_ASSET_VERSION ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-panel.css?v=<?= SAHP_ASSET_VERSION ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/espace-medias.css?v=<?= SAHP_ASSET_VERSION ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-espace-medias.css?v=<?= SAHP_ASSET_VERSION ?>">
</head>
<body class="admin-panel-body">
<?php require __DIR__ . '/../includes/header.php'; ?>
<?php
}

function km_admin_foot(): void
{
    ?>
<div class="mk-toast" id="mk-toast" role="status" aria-live="polite"></div>
<script src="<?= BASE_URL ?>/admin/js/espace-medias-admin.js?v=<?= SAHP_ASSET_VERSION ?>" defer></script>
</body>
</html>
<?php
}

function km_render_flash(array $flash): void
{
    foreach ($flash as [$t, $m]) {
        $ok = $t === 'ok';
        echo '<p class="admin-panel-alert ' . ($ok ? 'admin-panel-alert-success' : 'admin-panel-alert-error') . '" role="' . ($ok ? 'status' : 'alert') . '">' . km_e($m) . '</p>';
    }
}

/* ---------- Page de modification ---------- */
$editId = (string) ($_GET['edit'] ?? '');
if ($editId !== '' && km_valid_id($editId)) {
    $idx = km_find_index($all, $editId);
    if ($idx === null) km_redirect();
    $it = $all[$idx];
    km_admin_head('Modifier un fichier | Espace médias | SAHP Admin'); ?>
<main class="admin-panel-main">
  <div class="admin-panel-container card-glass mk-admin">
    <p><a href="<?= km_e(km_admin_href()) ?>" class="mk-back">← Retour à la liste</a></p>
    <h1 class="admin-panel-page-title">Modifier « <?= km_e($it['title']) ?> »</h1>
    <?php km_render_flash($flash); ?>

    <div class="mk-edit-preview">
      <div class="mk-thumb mk-checker"><?= km_thumb_url($it) ? '<img src="' . km_e(km_thumb_url($it)) . '" alt="">' : km_file_icon($it['ext']) ?></div>
      <div class="mk-meta">
        <?= km_e(km_meta($it)) ?><br>
        Fichier d'origine : <?= km_e($it['original_name'] ?? '') ?><br>
        Ajouté le <?= km_e(date('d/m/Y', strtotime((string) $it['created_at']))) ?> · <?= (int) ($it['downloads'] ?? 0) ?> téléchargement(s)<br>
        <a href="<?= km_e(km_href(km_download_path($it['id']))) ?>">Télécharger l'original</a>
      </div>
    </div>

    <form method="post" enctype="multipart/form-data" action="<?= km_e(km_admin_href()) ?>">
      <?= km_csrf_field() ?>
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= km_e($it['id']) ?>">
      <input type="hidden" name="back" value="edit">
      <div class="mk-form-grid">
        <div class="mk-field"><label for="title">Titre</label><input id="title" type="text" name="title" maxlength="120" value="<?= km_e($it['title']) ?>" required></div>
        <div class="mk-field"><label for="category">Catégorie</label><?= km_cat_select($it['category']) ?></div>
        <div class="mk-field mk-full"><label for="description">Description <span class="mk-hint">(facultatif — ex. « Format impression 85 × 55 mm, fond perdu 3 mm »)</span></label><textarea id="description" name="description" maxlength="600"><?= km_e($it['description'] ?? '') ?></textarea></div>
        <div class="mk-field"><label for="order">Position <span class="mk-hint">(plus petit = en premier)</span></label><input id="order" type="number" name="order" value="<?= (int) ($it['order'] ?? 0) ?>" min="-999" max="999"></div>
        <div class="mk-field mk-field-end"><label class="mk-check"><input type="checkbox" name="visible" value="1"<?= !empty($it['visible']) ? ' checked' : '' ?>> Visible sur la page publique</label></div>
        <div class="mk-field"><label for="file">Remplacer le fichier <span class="mk-hint">(garde le même lien)</span></label><input id="file" type="file" name="file" accept="<?= km_e(km_accept_attr()) ?>"></div>
        <div class="mk-field"><label for="preview">Image d'aperçu <span class="mk-hint">(JPG/PNG — utile pour PDF, AI, EPS, ZIP)</span></label><input id="preview" type="file" name="preview" accept=".jpg,.jpeg,.png,.webp">
          <?php if (!empty($it['preview'])): ?><label class="mk-check"><input type="checkbox" name="remove_preview" value="1"> Retirer l'aperçu actuel</label><?php endif; ?>
        </div>
      </div>
      <div class="mk-form-actions">
        <button class="admin-auth-submit mk-submit" type="submit">Enregistrer</button>
        <a class="admin-panel-secondary-btn" href="<?= km_e(km_admin_href()) ?>">Annuler</a>
      </div>
    </form>
  </div>
</main>
<?php km_admin_foot();
    exit;
}

/* ---------- Tableau de bord ---------- */
$total = count($all);
$downloads = array_sum(array_map(fn($i) => (int) ($i['downloads'] ?? 0), $all));
km_admin_head('Espace médias | SAHP Admin'); ?>
<main class="admin-panel-main">
  <div class="admin-panel-container card-glass mk-admin">
    <h1 class="admin-panel-page-title">Espace médias</h1>
    <p class="admin-panel-help">
      Fichiers proposés au téléchargement sur
      <a href="<?= km_e(km_href('espace-medias')) ?>" target="_blank" rel="noopener">la page publique Espace médias</a>.
    </p>
    <?php km_render_flash($flash); ?>
    <?php if (!is_writable(KM_DATA) || !is_writable(KM_STORAGE) || !is_writable(KM_THUMBS)): ?>
      <p class="admin-panel-alert admin-panel-alert-error" role="alert">Les dossiers <code>data/espace-medias/</code> et <code>public/uploads/espace-medias/thumbs/</code> doivent être accessibles en écriture (droits 755, ou 775 via FileZilla).</p>
    <?php endif; ?>

    <section class="admin-panel-section">
      <h2 class="admin-panel-subtitle">Ajouter des fichiers</h2>
      <form method="post" enctype="multipart/form-data" id="mk-upload-form" action="<?= km_e(km_admin_href()) ?>">
        <?= km_csrf_field() ?><input type="hidden" name="action" value="upload">
        <label class="mk-dropzone" id="mk-dropzone">
          <input type="file" name="files[]" id="mk-files" multiple required accept="<?= km_e(km_accept_attr()) ?>">
          <strong>Glissez vos fichiers ici ou cliquez pour choisir</strong>
          <span>Images (JPG, PNG, WEBP, SVG), PDF, AI, EPS, ZIP, MP4 — <?= (int) km_cfg('max_upload_mb') ?> Mo max par fichier
            (serveur : <?= km_e(ini_get('upload_max_filesize')) ?> par fichier, <?= km_e(ini_get('post_max_size')) ?> par envoi)</span>
          <ul class="mk-file-list" id="mk-file-list"></ul>
        </label>
        <div class="mk-form-grid">
          <div class="mk-field"><label for="category">Catégorie</label><?= km_cat_select((string) array_key_first($cats)) ?></div>
          <div class="mk-field"><label for="title">Titre <span class="mk-hint">(si un seul fichier ; sinon le nom du fichier est utilisé)</span></label><input id="title" type="text" name="title" maxlength="120" placeholder="ex. Logo SAHP couleur"></div>
          <div class="mk-field mk-full"><label for="description">Description <span class="mk-hint">(facultatif, appliquée à tous les fichiers envoyés)</span></label><textarea id="description" name="description" maxlength="600" placeholder="ex. Version fond transparent, idéale pour le web"></textarea></div>
          <div class="mk-field mk-full"><label class="mk-check"><input type="checkbox" name="visible" value="1" checked> Visible tout de suite sur la page publique</label></div>
        </div>
        <div class="mk-form-actions">
          <button class="admin-auth-submit mk-submit" type="submit" id="mk-upload-btn">Envoyer</button>
        </div>
      </form>
    </section>

    <section>
      <h2 class="admin-panel-subtitle">Vos fichiers <span class="mk-meta">— <?= $total ?> fichier<?= $total > 1 ? 's' : '' ?> · <?= $downloads ?> téléchargement<?= $downloads > 1 ? 's' : '' ?> au total</span></h2>
      <?php if (!$all): ?>
        <div class="mk-empty">Aucun fichier pour l'instant. Ajoutez vos logos, cartes de visite et plaquettes ci-dessus.</div>
      <?php else:
        $lastCat = null; ?>
        <div class="mk-list">
        <?php foreach ($all as $it):
            if ($it['category'] !== $lastCat): $lastCat = $it['category']; ?>
            <div class="mk-cat-title"><?= km_e(km_cat_label($it['category'])) ?></div>
        <?php endif; ?>
          <div class="mk-row">
            <div class="mk-mini"><?= km_mini($it) ?></div>
            <div class="mk-info">
              <strong><?= km_e($it['title']) ?></strong>
              <div class="mk-meta">
                <span class="mk-pill<?= empty($it['visible']) ? ' mk-off' : '' ?>"><?= empty($it['visible']) ? 'Masqué' : 'Visible' ?></span>
                <?= km_e(km_meta($it)) ?> · <?= (int) ($it['downloads'] ?? 0) ?> ⤓
              </div>
            </div>
            <div class="mk-tools">
              <a class="mk-btn mk-btn-ghost mk-btn-sm" href="<?= km_e(km_admin_href('edit=' . $it['id'])) ?>"><?= km_icon('edit') ?> Modifier</a>
              <form method="post" action="<?= km_e(km_admin_href()) ?>"><?= km_csrf_field() ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= km_e($it['id']) ?>"><button class="mk-btn mk-btn-ghost mk-btn-sm" type="submit"><?= empty($it['visible']) ? 'Afficher' : 'Masquer' ?></button></form>
              <button class="mk-btn mk-btn-ghost mk-btn-sm" type="button" data-copy="<?= km_e(km_absolute_url(km_download_path($it['id']))) ?>" title="Copier le lien de téléchargement" aria-label="Copier le lien"><?= km_icon('link') ?></button>
              <form method="post" action="<?= km_e(km_admin_href()) ?>" data-confirm="<?= km_e('Supprimer définitivement « ' . $it['title'] . ' » ?') ?>"><?= km_csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= km_e($it['id']) ?>"><button class="mk-btn mk-btn-danger mk-btn-sm" type="submit" aria-label="Supprimer"><?= km_icon('trash') ?></button></form>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </div>
</main>
<?php km_admin_foot();
