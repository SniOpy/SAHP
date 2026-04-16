<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_csrf.php';
require_once __DIR__ . '/../includes/article_form_helpers.php';
require_once __DIR__ . '/../includes/blog_file_upload.php';

requireAdminLogin();

$adminPageTitle = 'Ajouter un article | SAHP Admin';

$formSuccessMessage = '';
$formErrorMessage = '';

$fieldTitle = '';
$fieldSlug = '';
$fieldExcerpt = '';
$fieldContent = '';
$fieldCoverImageUrl = '';
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

$lastUploadedImageUrl = '';
$lastUploadedHtmlExample = '';
if (!empty($_SESSION['admin_upload_flash_image_url'])) {
    $lastUploadedImageUrl = (string) $_SESSION['admin_upload_flash_image_url'];
    unset($_SESSION['admin_upload_flash_image_url']);
}
if (!empty($_SESSION['admin_upload_flash_html_example'])) {
    $lastUploadedHtmlExample = (string) $_SESSION['admin_upload_flash_html_example'];
    unset($_SESSION['admin_upload_flash_html_example']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyAdminCsrfTokenFromPost()) {
        $formErrorMessage = 'Session expirée ou formulaire invalide. Rechargez la page.';
    } else {
        $fieldTitle = trim((string) ($_POST['title'] ?? ''));
        $fieldSlug = trim((string) ($_POST['slug'] ?? ''));
        $fieldExcerpt = (string) ($_POST['excerpt'] ?? '');
        $fieldContent = (string) ($_POST['content'] ?? '');
        $fieldCoverImageUrl = trim((string) ($_POST['cover_image_url'] ?? ''));
        $fieldCategory = trim((string) ($_POST['category'] ?? ''));
        $fieldTags = trim((string) ($_POST['tags'] ?? ''));
        $fieldIsPublished = isset($_POST['is_published']);
        $fieldPublishedAt = trim((string) ($_POST['published_at'] ?? ''));

        $validationErrors = [];

        if ($fieldTitle === '' || strlen($fieldTitle) > 255) {
            $validationErrors[] = 'Le titre est obligatoire (255 caractères maximum).';
        }

        if (trim($fieldContent) === '') {
            $validationErrors[] = 'Le contenu est obligatoire.';
        }

        $articleSlug = $fieldSlug !== '' ? normalizeArticleSlug($fieldSlug) : generateArticleSlugFromTitle($fieldTitle);
        if (!isArticleSlugValid($articleSlug)) {
            $validationErrors[] = 'Le slug est invalide (lettres minuscules, chiffres et tirets uniquement).';
        }

        $sanitizedExcerpt = sanitizeArticleExcerpt($fieldExcerpt);
        if (strlen($sanitizedExcerpt) > 65535) {
            $validationErrors[] = 'L\'extrait est trop long.';
        }

        $sanitizedContent = sanitizeArticleContentHtml($fieldContent);
        if (trim($sanitizedContent) === '') {
            $validationErrors[] = 'Le contenu est obligatoire après nettoyage HTML.';
        }
        if (strlen($sanitizedContent) > 16777215) {
            $validationErrors[] = 'Le contenu est trop long.';
        }

        if ($fieldCategory !== '' && strlen($fieldCategory) > 120) {
            $validationErrors[] = 'La catégorie est trop longue (120 caractères maximum).';
        }

        $tagsList = parseArticleTagsFromCommaString($fieldTags);
        $tagsJson = null;
        try {
            $tagsJson = encodeArticleTagsAsJson($tagsList);
        } catch (Throwable $exception) {
            $validationErrors[] = 'Les tags sont invalides.';
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
                } else {
                    $publishedAtValue = $publishedAtDateTime->format('Y-m-d H:i:s');
                }
            }
        }

        $coverImageStoredValue = null;
        if ($fieldCoverImageUrl !== '') {
            if (strlen($fieldCoverImageUrl) > 255) {
                $validationErrors[] = 'L\'URL de l\'image de couverture est trop longue.';
            } else {
                $coverImageStoredValue = $fieldCoverImageUrl;
            }
        }

        $coverFile = $_FILES['cover_image_file'] ?? null;
        if (is_array($coverFile) && ($coverFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            try {
                $coverFileName = saveBlogImageFromUploadedFile($coverFile);
                $coverImageStoredValue = buildBlogImagePublicUrl($coverFileName);
            } catch (Throwable $exception) {
                $validationErrors[] = 'Image de couverture : ' . $exception->getMessage();
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
                $sqlErrorCode = (int) ($pdoException->errorInfo[1] ?? 0);
                if ($sqlErrorCode === 1062 || str_contains($pdoException->getMessage(), 'Duplicate')) {
                    $formErrorMessage = 'Ce slug existe déjà. Modifiez le slug ou le titre.';
                } else {
                    $formErrorMessage = 'Erreur base de données lors de l\'enregistrement.';
                }
            } catch (Throwable $exception) {
                $formErrorMessage = 'Erreur technique lors de l\'enregistrement.';
            }
        }
    }
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
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=20260209-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-auth.css?v=20260413-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-panel.css?v=20260416-1">
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

            <?php if ($lastUploadedImageUrl !== ''): ?>
                <section class="admin-panel-upload-result" aria-labelledby="upload-result-title">
                    <h2 id="upload-result-title" class="admin-panel-subtitle">Image insérée (copiez dans le contenu)</h2>
                    <p class="admin-panel-help">URL générée :</p>
                    <p class="admin-panel-code" id="last-uploaded-image-url"><?= htmlspecialchars($lastUploadedImageUrl, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php if ($lastUploadedHtmlExample !== ''): ?>
                        <p class="admin-panel-help">Exemple HTML :</p>
                        <pre class="admin-panel-pre" id="last-uploaded-html-example"><?= htmlspecialchars($lastUploadedHtmlExample, ENT_QUOTES, 'UTF-8') ?></pre>
                    <?php endif; ?>
                    <button type="button" class="admin-panel-secondary-btn" id="copy-image-url-btn">Copier l'URL</button>
                    <?php if ($lastUploadedHtmlExample !== ''): ?>
                        <button type="button" class="admin-panel-secondary-btn" id="copy-html-snippet-btn">Copier l'exemple HTML</button>
                    <?php endif; ?>
                </section>
            <?php endif; ?>

            <section class="admin-panel-section" aria-labelledby="content-image-upload-title">
                <h2 id="content-image-upload-title" class="admin-panel-subtitle">Insérer une image dans le contenu</h2>
                <p class="admin-panel-help">Téléversez une image (jpg, png, webp, max 2 Mo), puis copiez l'URL ou le fragment HTML dans le champ contenu.</p>
                <form
                    class="admin-panel-inline-form"
                    method="post"
                    action="<?= BASE_URL ?>/admin/articles/upload-content-image.php"
                    enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <div class="admin-field-group admin-field-group-inline">
                        <label for="content_image_file">Fichier image</label>
                        <input id="content_image_file" name="content_image_file" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>
                    </div>
                    <button type="submit" class="admin-panel-secondary-btn">Insérer image</button>
                </form>
            </section>

            <form class="admin-panel-form" method="post" action="<?= BASE_URL ?>/admin/articles/create.php" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

                <div class="admin-field-group">
                    <label for="title">Titre <span class="admin-required">*</span></label>
                    <input id="title" name="title" type="text" maxlength="255" required value="<?= htmlspecialchars($fieldTitle, ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="admin-field-group">
                    <label for="slug">Slug (optionnel, généré depuis le titre si vide)</label>
                    <input id="slug" name="slug" type="text" maxlength="255" placeholder="exemple-mon-article" value="<?= htmlspecialchars($fieldSlug, ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="admin-field-group">
                    <label for="excerpt">Extrait</label>
                    <textarea id="excerpt" name="excerpt" rows="4" class="admin-textarea"><?= htmlspecialchars($fieldExcerpt, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="admin-field-group">
                    <label for="content">Contenu (HTML autorise) <span class="admin-required">*</span></label>
                    <textarea id="content" name="content" rows="16" class="admin-textarea admin-textarea-code" required><?= htmlspecialchars($fieldContent, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="admin-field-group">
                    <label for="cover_image_url">Image de couverture (URL)</label>
                    <input id="cover_image_url" name="cover_image_url" type="url" maxlength="255" placeholder="https://..." value="<?= htmlspecialchars($fieldCoverImageUrl, ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="admin-field-group">
                    <label for="cover_image_file">Image de couverture (fichier, prioritaire sur l'URL)</label>
                    <input id="cover_image_file" name="cover_image_file" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                </div>

                <div class="admin-field-group">
                    <label for="category">Catégorie</label>
                    <input id="category" name="category" type="text" maxlength="120" value="<?= htmlspecialchars($fieldCategory, ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="admin-field-group">
                    <label for="tags">Tags (séparés par des virgules)</label>
                    <input id="tags" name="tags" type="text" placeholder="curage, urgence, debouchage" value="<?= htmlspecialchars($fieldTags, ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="admin-field-group admin-field-checkbox">
                    <input id="is_published" name="is_published" type="checkbox" value="1" <?= $fieldIsPublished ? 'checked' : '' ?>>
                    <label for="is_published">Publier l'article</label>
                </div>

                <div class="admin-field-group">
                    <label for="published_at">Date de publication</label>
                    <input id="published_at" name="published_at" type="datetime-local" value="<?= htmlspecialchars($fieldPublishedAt, ENT_QUOTES, 'UTF-8') ?>">
                    <p class="admin-field-hint">Si publié sans date, la date du jour est utilisée. Si non publié, la date est ignorée.</p>
                </div>

                <button type="submit" class="admin-auth-submit">Enregistrer l'article</button>
            </form>
        </div>
    </main>

    <?php if ($lastUploadedImageUrl !== ''): ?>
        <script>
            (function() {
                var urlEl = document.getElementById('last-uploaded-image-url');
                var htmlEl = document.getElementById('last-uploaded-html-example');
                var copyUrlBtn = document.getElementById('copy-image-url-btn');
                var copyHtmlBtn = document.getElementById('copy-html-snippet-btn');

                function copyText(text) {
                    if (!text) return;
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text);
                    } else {
                        var ta = document.createElement('textarea');
                        ta.value = text;
                        document.body.appendChild(ta);
                        ta.select();
                        document.execCommand('copy');
                        document.body.removeChild(ta);
                    }
                }

                if (copyUrlBtn && urlEl) {
                    copyUrlBtn.addEventListener('click', function() {
                        copyText(urlEl.textContent || '');
                    });
                }
                if (copyHtmlBtn && htmlEl) {
                    copyHtmlBtn.addEventListener('click', function() {
                        copyText(htmlEl.textContent || '');
                    });
                }
            })();
        </script>
    <?php endif; ?>
</body>

</html>