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

/**
 * Réponse JSON pour l'éditeur à blocs (fetch) — sinon redirection + flash classique.
 */
function upload_content_image_wants_json_response(): bool
{
    $flag = (string) ($_POST['response_format'] ?? '');
    if ($flag === 'json') {
        return true;
    }

    $acceptHeader = (string) ($_SERVER['HTTP_ACCEPT'] ?? '');

    return str_contains($acceptHeader, 'application/json');
}

/**
 * @param array<string, string> $payload
 */
function upload_content_image_send_json(bool $ok, array $payload, string $message): void
{
    header('Content-Type: application/json; charset=UTF-8');
    header('X-Content-Type-Options: nosniff');
    $response = [
        'ok' => $ok,
        'message' => $message,
    ];
    if ($payload !== []) {
        $response = array_merge($response, $payload);
    }
    echo json_encode($response, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/articles/create.php');
    exit;
}

if (!verifyAdminCsrfTokenFromPost()) {
    if (upload_content_image_wants_json_response()) {
        upload_content_image_send_json(false, [], 'Session expirée ou formulaire invalide. Rechargez la page.');
    }
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

    if (upload_content_image_wants_json_response()) {
        upload_content_image_send_json(true, [
            'url' => $publicImageUrl,
            'filename' => $savedFileName,
        ], '');
    }

    $_SESSION['admin_upload_flash_image_url'] = $publicImageUrl;
    $_SESSION['admin_upload_flash_html_example'] = '<img src="' . htmlspecialchars($publicImageUrl, ENT_QUOTES, 'UTF-8') . '" alt="Illustration">';
} catch (Throwable $exception) {
    if (upload_content_image_wants_json_response()) {
        upload_content_image_send_json(false, [], $exception->getMessage());
    }
    $_SESSION['admin_upload_flash_error'] = $exception->getMessage();
}

header('Location: ' . buildAdminUploadRedirectUrl());
exit;
