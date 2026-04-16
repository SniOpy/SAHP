<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_csrf.php';
require_once __DIR__ . '/../includes/blog_file_upload.php';

requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/articles/create.php');
    exit;
}

if (!verifyAdminCsrfTokenFromPost()) {
    $_SESSION['admin_upload_flash_error'] = 'Session expiree ou formulaire invalide. Rechargez la page.';
    header('Location: ' . BASE_URL . '/admin/articles/create.php');
    exit;
}

try {
    $uploadedFile = $_FILES['content_image_file'] ?? null;
    if (!is_array($uploadedFile)) {
        throw new RuntimeException('Aucun fichier image recu.');
    }

    $savedFileName = saveBlogImageFromUploadedFile($uploadedFile);
    $publicImageUrl = buildBlogImagePublicUrl($savedFileName);

    $_SESSION['admin_upload_flash_image_url'] = $publicImageUrl;
    $_SESSION['admin_upload_flash_html_example'] = '<img src="' . htmlspecialchars($publicImageUrl, ENT_QUOTES, 'UTF-8') . '" alt="Illustration">';
} catch (Throwable $exception) {
    $_SESSION['admin_upload_flash_error'] = $exception->getMessage();
}

header('Location: ' . BASE_URL . '/admin/articles/create.php');
exit;
