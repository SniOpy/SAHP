<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_csrf.php';

requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/articles/index.php');
    exit;
}

if (!verifyAdminCsrfTokenFromPost()) {
    $_SESSION['admin_articles_flash_error'] = 'Session expirée ou formulaire invalide.';
    header('Location: ' . BASE_URL . '/admin/articles/index.php');
    exit;
}

$articleId = (int) ($_POST['article_id'] ?? 0);

if ($articleId < 1) {
    $_SESSION['admin_articles_flash_error'] = 'Identifiant article invalide.';
    header('Location: ' . BASE_URL . '/admin/articles/index.php');
    exit;
}

try {
    $databaseConnection = getDatabaseConnection();
    $deleteQuery = $databaseConnection->prepare('DELETE FROM articles WHERE id = :id LIMIT 1');
    $deleteQuery->execute(['id' => $articleId]);

    if ($deleteQuery->rowCount() === 0) {
        $_SESSION['admin_articles_flash_error'] = 'Article introuvable.';
    } else {
        $_SESSION['admin_articles_flash_success'] = 'Article supprimé.';
    }
} catch (Throwable $exception) {
    $_SESSION['admin_articles_flash_error'] = 'Erreur lors de la suppression.';
}

header('Location: ' . BASE_URL . '/admin/articles/index.php');
exit;
