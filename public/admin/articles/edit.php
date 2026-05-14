<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_csrf.php';
require_once __DIR__ . '/../includes/article_form_helpers.php';
require_once __DIR__ . '/../includes/blog_file_upload.php';
require_once APP_PATH . '/helpers/article_blocks.php';
require_once APP_PATH . '/helpers/blog.php';

requireAdminLogin();

$adminPageTitle = 'Modifier un article | SAHP Admin';

$articleId = (int) ($_GET['id'] ?? 0);

if ($articleId < 1) {
    header('Location: ' . BASE_URL . '/admin/articles/index.php');
    exit;
}

$loadedArticle = null;

try {
    $databaseConnection = getDatabaseConnection();
    $selectQuery = $databaseConnection->prepare('SELECT * FROM articles WHERE id = :id LIMIT 1');
    $selectQuery->execute(['id' => $articleId]);
    $loadedArticle = $selectQuery->fetch();
} catch (Throwable $exception) {
    $loadedArticle = null;
}

if ($loadedArticle === false || $loadedArticle === null) {
    header('Location: ' . BASE_URL . '/admin/articles/index.php');
    exit;
}

$formSuccessMessage = '';
$formErrorMessage = '';
$formFieldErrors = [];

if (!empty($_GET['updated'])) {
    $formSuccessMessage = 'Article enregistré avec succès.';
}

$fieldTitle = (string) ($loadedArticle['title'] ?? '');
$fieldSlug = (string) ($loadedArticle['slug'] ?? '');
$fieldExcerpt = (string) ($loadedArticle['excerpt'] ?? '');
$fieldContent = (string) ($loadedArticle['content'] ?? '');
/** Valeur persistée en base (aperçu + conservation si aucun nouveau fichier à l'enregistrement). */
$currentCoverImageStored = trim((string) ($loadedArticle['cover_image'] ?? ''));

$fieldCategory = (string) ($loadedArticle['category'] ?? '');
$fieldIsPublished = (int) ($loadedArticle['is_published'] ?? 0) === 1;

$tagsRaw = $loadedArticle['tags'] ?? null;
$fieldTags = '';
if (is_string($tagsRaw) && $tagsRaw !== '') {
    $decodedTags = json_decode($tagsRaw, true);
    if (is_array($decodedTags)) {
        $fieldTags = implode(', ', $decodedTags);
    }
}

$fieldPublishedAt = '';
$publishedAtDb = $loadedArticle['published_at'] ?? null;
if (is_string($publishedAtDb) && $publishedAtDb !== '') {
    $publishedDateTime = date_create($publishedAtDb);
    if ($publishedDateTime instanceof DateTimeInterface) {
        $fieldPublishedAt = $publishedDateTime->format('Y-m-d\TH:i');
    }
}

if (!empty($_SESSION['admin_upload_flash_error'])) {
    $formErrorMessage = (string) $_SESSION['admin_upload_flash_error'];
    unset($_SESSION['admin_upload_flash_error']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyAdminCsrfTokenFromPost()) {
        $formErrorMessage = 'Session expirée ou formulaire invalide. Rechargez la page.';
    } else {
        $postedArticleId = (int) ($_POST['article_id'] ?? 0);
        if ($postedArticleId !== $articleId) {
            $formErrorMessage = 'Identifiant article incohérent.';
        } else {
            $fieldTitle = trim((string) ($_POST['title'] ?? ''));
            $fieldSlug = trim((string) ($_POST['slug'] ?? ''));
            $fieldExcerpt = (string) ($_POST['excerpt'] ?? '');
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

            $canEditAdvancedHtml = adminUserCanEditRawArticleHtml();
            $contentResolution = resolve_raw_article_html_from_editor_submission($_POST, $canEditAdvancedHtml);
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

            $coverImageStoredValue = $currentCoverImageStored !== '' ? $currentCoverImageStored : null;

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

            if ($validationErrors === []) {
                try {
                    $duplicateCheck = $databaseConnection->prepare(
                        'SELECT id FROM articles WHERE slug = :slug AND id != :id LIMIT 1'
                    );
                    $duplicateCheck->execute(['slug' => $articleSlug, 'id' => $articleId]);
                    if ($duplicateCheck->fetch() !== false) {
                        $validationErrors[] = 'Ce slug est déjà utilisé par un autre article.';
                        $formFieldErrors['slug'] = 'Ce slug est déjà utilisé. Choisissez-en un autre.';
                    }
                } catch (Throwable $exception) {
                    $validationErrors[] = 'Erreur lors de la vérification du slug.';
                }
            }

            if ($validationErrors !== []) {
                $formErrorMessage = implode(' ', $validationErrors);
            } else {
                try {
                    $updateQuery = $databaseConnection->prepare(
                        'UPDATE articles SET
                            title = :title,
                            slug = :slug,
                            excerpt = :excerpt,
                            content = :content,
                            cover_image = :cover_image,
                            category = :category,
                            tags = :tags,
                            is_published = :is_published,
                            published_at = :published_at
                        WHERE id = :id
                        LIMIT 1'
                    );

                    $updateQuery->execute([
                        'title' => $fieldTitle,
                        'slug' => $articleSlug,
                        'excerpt' => $sanitizedExcerpt !== '' ? $sanitizedExcerpt : null,
                        'content' => $sanitizedContent,
                        'cover_image' => $coverImageStoredValue,
                        'category' => $fieldCategory !== '' ? $fieldCategory : null,
                        'tags' => $tagsJson,
                        'is_published' => $isPublishedValue,
                        'published_at' => $publishedAtValue,
                        'id' => $articleId,
                    ]);

                    header('Location: ' . BASE_URL . '/admin/articles/edit.php?id=' . $articleId . '&updated=1');
                    exit;
                } catch (PDOException $pdoException) {
                    $sqlErrorCode = (int) ($pdoException->errorInfo[1] ?? 0);
                    if ($sqlErrorCode === 1062 || str_contains($pdoException->getMessage(), 'Duplicate')) {
                        $formFieldErrors['slug'] = 'Ce slug est déjà utilisé. Choisissez-en un autre.';
                        $formErrorMessage = 'Ce slug existe déjà. Modifiez le slug.';
                    } else {
                        $formErrorMessage = 'Erreur base de données lors de l\'enregistrement.';
                    }
                } catch (Throwable $exception) {
                    $formErrorMessage = 'Erreur technique lors de l\'enregistrement.';
                }
            }
        }
    }
}

$canEditAdvancedHtml = adminUserCanEditRawArticleHtml();
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
        rel="stylesheet"
    >
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=<?= SAHP_ASSET_VERSION ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-auth.css?v=<?= SAHP_ASSET_VERSION ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-panel.css?v=<?= SAHP_ASSET_VERSION ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-article-blocks.css?v=<?= SAHP_ASSET_VERSION ?>">
</head>
<body class="admin-panel-body">
<?php require __DIR__ . '/../includes/header.php'; ?>

<main class="admin-panel-main">
    <div class="admin-panel-container card-glass">
        <h1 class="admin-panel-page-title">Modifier l’article</h1>

        <?php if ($formSuccessMessage !== ''): ?>
            <p class="admin-panel-alert admin-panel-alert-success" role="status"><?= htmlspecialchars($formSuccessMessage, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if ($formErrorMessage !== ''): ?>
            <p class="admin-panel-alert admin-panel-alert-error" role="alert"><?= htmlspecialchars($formErrorMessage, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form class="admin-panel-form admin-article-blocks-form" method="post" action="<?= BASE_URL ?>/admin/articles/edit.php?id=<?= (int) $articleId ?>" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="article_id" value="<?= (int) $articleId ?>">

            <div id="field-title" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'title'), ENT_QUOTES, 'UTF-8') ?>">
                <label for="title">Titre <span class="admin-required">*</span></label>
                <input id="title" name="title" type="text" maxlength="255" required value="<?= htmlspecialchars($fieldTitle, ENT_QUOTES, 'UTF-8') ?>" <?= isset($formFieldErrors['title']) ? ' aria-invalid="true"' : '' ?>>
                <?= admin_article_field_error_notice($formFieldErrors, 'title') ?>
            </div>

            <div id="field-slug" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'slug'), ENT_QUOTES, 'UTF-8') ?>">
                <label for="slug">Slug</label>
                <input id="slug" name="slug" type="text" maxlength="255" placeholder="exemple-mon-article" value="<?= htmlspecialchars($fieldSlug, ENT_QUOTES, 'UTF-8') ?>" <?= isset($formFieldErrors['slug']) ? ' aria-invalid="true"' : '' ?>>
                <?= admin_article_field_error_notice($formFieldErrors, 'slug') ?>
            </div>

            <div id="field-excerpt" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'excerpt'), ENT_QUOTES, 'UTF-8') ?>">
                <label for="excerpt">Extrait</label>
                <textarea id="excerpt" name="excerpt" rows="4" class="admin-textarea" <?= isset($formFieldErrors['excerpt']) ? ' aria-invalid="true"' : '' ?>><?= htmlspecialchars($fieldExcerpt, ENT_QUOTES, 'UTF-8') ?></textarea>
                <?= admin_article_field_error_notice($formFieldErrors, 'excerpt') ?>
            </div>

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
                <?php if ($currentCoverImageStored !== ''): ?>
                    <p class="admin-field-hint" style="margin-bottom:10px;">
                        <img src="<?= htmlspecialchars(blog_resolve_cover_image_url($currentCoverImageStored), ENT_QUOTES, 'UTF-8') ?>" alt="" width="240" loading="lazy" style="display:block;border-radius:6px;border:1px solid rgba(0,0,0,.1);max-width:100%;height:auto;">
                        <span>Couverture actuelle · choisissez un fichier ci-dessous pour la remplacer.</span>
                    </p>
                <?php endif; ?>
                <input id="cover_image_file" name="cover_image_file" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" <?= isset($formFieldErrors['cover_image_file']) ? ' aria-invalid="true"' : '' ?>>
                <p class="admin-field-hint">JPEG, PNG ou WebP depuis votre ordinateur (laisser vide pour conserver l’image actuelle).</p>
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

            <button type="submit" class="admin-auth-submit" id="article-form-submit">Enregistrer les modifications</button>
        </form>

        <p class="admin-panel-help" style="margin-top:20px;">
            <a href="<?= BASE_URL ?>/admin/articles/index.php">← Retour à la liste des articles</a>
        </p>
    </div>
</main>
</body>
</html>
