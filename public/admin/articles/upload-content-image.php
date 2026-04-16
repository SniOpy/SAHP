<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_csrf.php';
require_once __DIR__ . '/../includes/blog_file_upload.php';

requireAdminLogin();

/**
 * Retour vers la page création ou édition selon le formulaire d'upload.
 */
function buildAdminUploadRedirectUrl(): string
{
    $redirectTo = (string) ($_POST['redirect_to'] ?? 'create');
    $editArticleId = (int) ($_POST['edit_article_id'] ?? 0);

    if ($redirectTo === 'edit' && $editArticleId > 0) {
        return BASE_URL . '/admin/articles/edit.php?id=' . $editArticleId;
    }

    return BASE_URL . '/admin/articles/create.php';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/articles/create.php');
    exit;
}

if (!verifyAdminCsrfTokenFromPost()) {
    $_SESSION['admin_upload_flash_error'] = 'Session expirée ou formulaire invalide. Rechargez la page.';
    header('Location: ' . buildAdminUploadRedirectUrl());
    exit;
}

try {
    $uploadedFile = $_FILES['content_image_file'] ?? null;
    if (!is_array($uploadedFile)) {
        throw new RuntimeException('Aucun fichier image reçu.');
    }

    $savedFileName = saveBlogImageFromUploadedFile($uploadedFile);
    $publicImageUrl = buildBlogImagePublicUrl($savedFileName);

    $_SESSION['admin_upload_flash_image_url'] = $publicImageUrl;
    $_SESSION['admin_upload_flash_html_example'] = '<img src="' . htmlspecialchars($publicImageUrl, ENT_QUOTES, 'UTF-8') . '" alt="Illustration">';
} catch (Throwable $exception) {
    $_SESSION['admin_upload_flash_error'] = $exception->getMessage();
}

header('Location: ' . buildAdminUploadRedirectUrl());
exit;
