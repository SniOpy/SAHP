<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_csrf.php';
require_once APP_PATH . '/helpers/article_blocks.php';

header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');

requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Méthode non autorisée.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (! verifyAdminCsrfTokenFromPost()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'Session expirée ou formulaire invalide.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $blocks = parse_article_blocks_from_json_string((string) ($_POST['blocks_json'] ?? ''));
    $html = encode_article_blocks_as_html($blocks);
    echo json_encode(['ok' => true, 'html' => $html], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $exception) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'message' => $exception->getMessage() !== '' ? $exception->getMessage() : 'Conversion impossible.',
    ], JSON_UNESCAPED_UNICODE);
}
