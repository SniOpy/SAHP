<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_csrf.php';
require_once __DIR__ . '/../includes/article_form_helpers.php';
require_once __DIR__ . '/../includes/blog_file_upload.php';
require_once APP_PATH . '/helpers/article_blocks.php';

requireAdminLogin();

$adminPageTitle = 'Ajouter un article | SAHP Admin';

$formSuccessMessage = '';
$formErrorMessage = '';

$formFieldErrors = [];

$fieldTitle = '';
$fieldSlug = '';
$fieldExcerpt = '';
$fieldMetaTitle = '';
$fieldMetaDescription = '';
$fieldContent = '';
$fieldCategory = '';
$fieldTags = '';
$fieldIsPublished = false;
$fieldPublishedAt = '';

if (!empty($_GET['created'])) {
    $formSuccessMessage = 'Article créé avec succès.';
}

if (!empty($_SESSION['admin_upload_flash_error'])) {
    $formErrorMessage = (string) $_SESSION['admin_upload_flash_error'];
    unset($_SESSION['admin_upload_flash_error']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyAdminCsrfTokenFromPost()) {
        $formErrorMessage = 'Session expirée ou formulaire invalide. Rechargez la page.';
    } else {
        $fieldTitle = trim((string) ($_POST['title'] ?? ''));
        $fieldSlug = trim((string) ($_POST['slug'] ?? ''));
        $fieldExcerpt = (string) ($_POST['excerpt'] ?? '');
        $fieldMetaTitle = trim((string) ($_POST['meta_title'] ?? ''));
        $fieldMetaDescription = trim((string) ($_POST['meta_description'] ?? ''));
        $fieldCategory = trim((string) ($_POST['category'] ?? ''));
        $fieldTags = trim((string) ($_POST['tags'] ?? ''));
        $fieldIsPublished = isset($_POST['is_published']);
        $fieldPublishedAt = trim((string) ($_POST['published_at'] ?? ''));

        $validationErrors = [];
        $formFieldErrors = [];

        if ($fieldTitle === '' || strlen($fieldTitle) > 255) {
            $validationErrors[] = 'Le titre est obligatoire (255 caractères maximum).';
            $formFieldErrors['title'] = 'Indiquez un titre.';
        }

        $contentResolution = resolve_raw_article_html_from_editor_submission($_POST, true);
        $fieldContent = $contentResolution['html'];
        if ($contentResolution['error'] !== null) {
            $validationErrors[] = $contentResolution['error'];
            $formFieldErrors['content'] = $contentResolution['error'];
        }

        $articleSlug = $fieldSlug !== '' ? normalizeArticleSlug($fieldSlug) : generateArticleSlugFromTitle($fieldTitle);
        if (!isArticleSlugValid($articleSlug)) {
            $validationErrors[] = 'Le slug est invalide (lettres minuscules, chiffres et tirets uniquement).';
            $formFieldErrors['slug'] = 'Slug invalide (ex. mon-article-2025).';
        }

        $sanitizedExcerpt = sanitizeArticleExcerpt($fieldExcerpt);
        if (strlen($sanitizedExcerpt) > 65535) {
            $validationErrors[] = 'L\'extrait est trop long.';
            $formFieldErrors['excerpt'] = 'Raccourcissez l’extrait (limite technique).';
        }

        validateArticleSeoFields($fieldMetaTitle, $fieldMetaDescription, $validationErrors, $formFieldErrors);
        $sanitizedMetaTitle = sanitizeArticleMetaTitle($fieldMetaTitle);
        $sanitizedMetaDescription = sanitizeArticleMetaDescription($fieldMetaDescription);

        $sanitizedContent = sanitizeArticleContentHtml($fieldContent);
        if (trim($sanitizedContent) === '') {
            $validationErrors[] = 'Le contenu est obligatoire après nettoyage HTML.';
            $formFieldErrors['content'] = 'Ajoutez du contenu (blocs ou HTML valide selon votre mode).';
        }
        if (strlen($sanitizedContent) > 16777215) {
            $validationErrors[] = 'Le contenu est trop long.';
            $formFieldErrors['content'] = ($formFieldErrors['content'] ?? '') !== ''
                ? $formFieldErrors['content']
                : 'Le contenu dépasse la taille maximale acceptée.';
        }

        if ($fieldCategory !== '' && strlen($fieldCategory) > 120) {
            $validationErrors[] = 'La catégorie est trop longue (120 caractères maximum).';
            $formFieldErrors['category'] = 'Raccourcissez la catégorie.';
        }

        $tagsList = parseArticleTagsFromCommaString($fieldTags);
        $tagsJson = null;
        try {
            $tagsJson = encodeArticleTagsAsJson($tagsList);
        } catch (Throwable $exception) {
            $validationErrors[] = 'Les tags sont invalides.';
            $formFieldErrors['tags'] = 'Vérifiez le format des tags.';
        }

        $isPublishedValue = $fieldIsPublished ? 1 : 0;
        $publishedAtValue = null;

        if ($fieldIsPublished) {
            if ($fieldPublishedAt === '') {
                $publishedAtValue = date('Y-m-d H:i:s');
            } else {
                $publishedAtDateTime = DateTime::createFromFormat('Y-m-d\TH:i', $fieldPublishedAt);
                if ($publishedAtDateTime === false) {
                    $validationErrors[] = 'La date de publication est invalide.';
                    $formFieldErrors['published_at'] = 'Choisissez une date et une heure valides.';
                } else {
                    $publishedAtValue = $publishedAtDateTime->format('Y-m-d H:i:s');
                }
            }
        }

        $coverImageStoredValue = null;
        if (! empty($_POST['remove_cover_image'])) {
            $coverImageStoredValue = null;
        }
        $coverFile = $_FILES['cover_image_file'] ?? null;
        if (is_array($coverFile) && ($coverFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            try {
                $coverFileName = saveBlogImageFromUploadedFile($coverFile);
                $coverImageStoredValue = buildBlogImagePublicUrl($coverFileName);
            } catch (Throwable $exception) {
                $validationErrors[] = 'Image de couverture : ' . $exception->getMessage();
                $formFieldErrors['cover_image_file'] = 'Image : ' . $exception->getMessage();
            }
        }

        if ($validationErrors !== []) {
            $formErrorMessage = implode(' ', $validationErrors);
        } else {
            try {
                $databaseConnection = getDatabaseConnection();
                $insertQuery = $databaseConnection->prepare(
                    'INSERT INTO articles (
                        title,
                        slug,
                        excerpt,
                        meta_title,
                        meta_description,
                        content,
                        cover_image,
                        category,
                        tags,
                        is_published,
                        published_at
                    ) VALUES (
                        :title,
                        :slug,
                        :excerpt,
                        :meta_title,
                        :meta_description,
                        :content,
                        :cover_image,
                        :category,
                        :tags,
                        :is_published,
                        :published_at
                    )'
                );

                $insertQuery->execute([
                    'title' => $fieldTitle,
                    'slug' => $articleSlug,
                    'excerpt' => $sanitizedExcerpt !== '' ? $sanitizedExcerpt : null,
                    'meta_title' => $sanitizedMetaTitle !== '' ? $sanitizedMetaTitle : null,
                    'meta_description' => $sanitizedMetaDescription !== '' ? $sanitizedMetaDescription : null,
                    'content' => $sanitizedContent,
                    'cover_image' => $coverImageStoredValue,
                    'category' => $fieldCategory !== '' ? $fieldCategory : null,
                    'tags' => $tagsJson,
                    'is_published' => $isPublishedValue,
                    'published_at' => $publishedAtValue,
                ]);

                header('Location: ' . BASE_URL . '/admin/articles/create.php?created=1');
                exit;
            } catch (PDOException $pdoException) {
                $dbError = admin_article_format_save_database_error($pdoException);
                if ($dbError === 'duplicate_slug') {
                    $formFieldErrors['slug'] = 'Ce slug est déjà utilisé. Choisissez-en un autre.';
                    $formErrorMessage = 'Ce slug existe déjà. Modifiez le slug ou le titre.';
                } else {
                    $formErrorMessage = $dbError;
                }
            } catch (Throwable $exception) {
                $formErrorMessage = 'Erreur technique lors de l\'enregistrement.';
            }
        }
    }
}

$initialEditorPayload = build_article_editor_initial_payload_from_html($fieldContent)['payload'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blocks_json'])) {
    $rawJson = trim((string) $_POST['blocks_json']);
    if ($rawJson !== '') {
        $rep = json_decode($rawJson, true);
        if (is_array($rep) && isset($rep['blocks']) && is_array($rep['blocks'])) {
            $initialEditorPayload = $rep;
            if (! isset($initialEditorPayload['version'])) {
                $initialEditorPayload['version'] = ARTICLE_BLOCKS_JSON_VERSION;
            }
        }
    }
}
$fieldContentRaw = $fieldContent;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && array_key_exists('content', $_POST)) {
    $fieldContentRaw = (string) $_POST['content'];
}

$csrfToken = ensureAdminCsrfToken();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($adminPageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=<?= SAHP_ASSET_VERSION ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-auth.css?v=<?= SAHP_ASSET_VERSION ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-panel.css?v=<?= SAHP_ASSET_VERSION ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-article-blocks.css?v=<?= SAHP_ASSET_VERSION ?>">
</head>

<body class="admin-panel-body">
    <?php require __DIR__ . '/../includes/header.php'; ?>

    <main class="admin-panel-main">
        <div class="admin-panel-container card-glass">
            <h1 class="admin-panel-page-title">Ajouter un article</h1>

            <?php if ($formSuccessMessage !== ''): ?>
                <p class="admin-panel-alert admin-panel-alert-success" role="status"><?= htmlspecialchars($formSuccessMessage, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <?php if ($formErrorMessage !== ''): ?>
                <p class="admin-panel-alert admin-panel-alert-error" role="alert"><?= htmlspecialchars($formErrorMessage, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form class="admin-panel-form admin-article-blocks-form" method="post" action="<?= BASE_URL ?>/admin/articles/create.php" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

                <div id="field-title" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'title'), ENT_QUOTES, 'UTF-8') ?>">
                    <label for="title">Titre <span class="admin-required">*</span></label>
                    <input id="title" name="title" type="text" maxlength="255" required value="<?= htmlspecialchars($fieldTitle, ENT_QUOTES, 'UTF-8') ?>" <?= isset($formFieldErrors['title']) ? ' aria-invalid="true"' : '' ?>>
                    <?= admin_article_field_error_notice($formFieldErrors, 'title') ?>
                </div>

                <div id="field-slug" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'slug'), ENT_QUOTES, 'UTF-8') ?>">
                    <label for="slug">Slug (optionnel, généré depuis le titre si vide)</label>
                    <input id="slug" name="slug" type="text" maxlength="255" placeholder="exemple-mon-article" value="<?= htmlspecialchars($fieldSlug, ENT_QUOTES, 'UTF-8') ?>" <?= isset($formFieldErrors['slug']) ? ' aria-invalid="true"' : '' ?>>
                    <?= admin_article_field_error_notice($formFieldErrors, 'slug') ?>
                </div>

                <div id="field-excerpt" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'excerpt'), ENT_QUOTES, 'UTF-8') ?>">
                    <label for="excerpt">Extrait</label>
                    <textarea id="excerpt" name="excerpt" rows="4" class="admin-textarea" <?= isset($formFieldErrors['excerpt']) ? ' aria-invalid="true"' : '' ?>><?= htmlspecialchars($fieldExcerpt, ENT_QUOTES, 'UTF-8') ?></textarea>
                    <?= admin_article_field_error_notice($formFieldErrors, 'excerpt') ?>
                </div>

                <?php require __DIR__ . '/../includes/article_seo_fields.php'; ?>

                <div
                    id="field-content"
                    class="<?= htmlspecialchars(trim(admin_article_field_group_class($formFieldErrors, 'content') . (!empty($formFieldErrors['content']) ? ' article-editor-visual-error' : '')), ENT_QUOTES, 'UTF-8') ?>"
                >
                    <p><strong>Contenu de l’article <span class="admin-required">*</span></strong></p>
                    <p class="admin-field-hint">Composez avec des blocs (texte, titres, images, listes, encadré). Aucun code HTML n’est nécessaire.</p>
                    <?= admin_article_field_error_notice($formFieldErrors, 'content') ?>
                    <?php
                    require __DIR__ . '/../includes/article_block_editor_section.php';
                    ?>
                </div>

                <div id="field-cover-image" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'cover_image_file'), ENT_QUOTES, 'UTF-8') ?>">
                    <label for="cover_image_file">Image de couverture</label>
                    <input id="cover_image_file" name="cover_image_file" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" <?= isset($formFieldErrors['cover_image_file']) ? ' aria-invalid="true"' : '' ?>>
                    <p class="admin-field-hint">JPEG, PNG ou WebP depuis votre ordinateur (optionnel).</p>
                    <label class="article-block-checkbox-label admin-field-checkbox" style="margin-top:10px;">
                        <input type="checkbox" name="remove_cover_image" value="1">
                        Supprimer l’image de couverture
                    </label>
                    <?= admin_article_field_error_notice($formFieldErrors, 'cover_image_file') ?>
                </div>

                <div id="field-category" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'category'), ENT_QUOTES, 'UTF-8') ?>">
                    <label for="category">Catégorie</label>
                    <input id="category" name="category" type="text" maxlength="120" value="<?= htmlspecialchars($fieldCategory, ENT_QUOTES, 'UTF-8') ?>" <?= isset($formFieldErrors['category']) ? ' aria-invalid="true"' : '' ?>>
                    <?= admin_article_field_error_notice($formFieldErrors, 'category') ?>
                </div>

                <div id="field-tags" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'tags'), ENT_QUOTES, 'UTF-8') ?>">
                    <label for="tags">Tags (séparés par des virgules)</label>
                    <input id="tags" name="tags" type="text" placeholder="curage, urgence, debouchage" value="<?= htmlspecialchars($fieldTags, ENT_QUOTES, 'UTF-8') ?>" <?= isset($formFieldErrors['tags']) ? ' aria-invalid="true"' : '' ?>>
                    <?= admin_article_field_error_notice($formFieldErrors, 'tags') ?>
                </div>

                <div class="admin-field-group admin-field-checkbox">
                    <input id="is_published" name="is_published" type="checkbox" value="1" <?= $fieldIsPublished ? 'checked' : '' ?>>
                    <label for="is_published">Publier l’article</label>
                    <p class="admin-field-hint">Sans publication, l’article reste en brouillon : visible ici dans l’admin, mais pas sur « Paroles de pro », pas sur les cartes du site ni sur la page individuelle publique.</p>
                </div>

                <div id="field-published-at" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'published_at'), ENT_QUOTES, 'UTF-8') ?>">
                    <label for="published_at">Date de publication</label>
                    <input id="published_at" name="published_at" type="datetime-local" value="<?= htmlspecialchars($fieldPublishedAt, ENT_QUOTES, 'UTF-8') ?>" <?= isset($formFieldErrors['published_at']) ? ' aria-invalid="true"' : '' ?>>
                    <p class="admin-field-hint">Si publié sans date, la date du jour est utilisée. Si non publié, la date est ignorée.</p>
                    <?= admin_article_field_error_notice($formFieldErrors, 'published_at') ?>
                </div>

                <button type="submit" class="admin-auth-submit" id="article-form-submit">Enregistrer l'article</button>
            </form>
        </div>
    </main>
</body>

</html>