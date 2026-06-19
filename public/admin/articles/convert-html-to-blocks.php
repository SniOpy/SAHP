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

$html = (string) ($_POST['content'] ?? '');
$blocks = try_decode_article_html_to_blocks($html);

if ($blocks === []) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'message' => 'Conversion impossible : HTML vide ou non reconnu. Conservez le mode Avancé ou simplifiez le HTML.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'ok' => true,
    'payload' => [
        'version' => ARTICLE_BLOCKS_JSON_VERSION,
        'blocks' => $blocks,
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
