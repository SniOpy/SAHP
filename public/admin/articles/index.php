<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_csrf.php';

requireAdminLogin();

$adminPageTitle = 'Articles | SAHP Admin';

$flashSuccessMessage = '';
$flashErrorMessage = '';
if (!empty($_SESSION['admin_articles_flash_success'])) {
    $flashSuccessMessage = (string) $_SESSION['admin_articles_flash_success'];
    unset($_SESSION['admin_articles_flash_success']);
}
if (!empty($_SESSION['admin_articles_flash_error'])) {
    $flashErrorMessage = (string) $_SESSION['admin_articles_flash_error'];
    unset($_SESSION['admin_articles_flash_error']);
}

$articlesList = [];

try {
    $databaseConnection = getDatabaseConnection();
    $statement = $databaseConnection->query(
        'SELECT id, title, slug, category, is_published, published_at, created_at, updated_at
         FROM articles
         ORDER BY updated_at DESC'
    );
    $articlesList = $statement->fetchAll();
} catch (Throwable $exception) {
    $articlesList = [];
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
        rel="stylesheet"
    >
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=20260209-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-auth.css?v=20260413-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-panel.css?v=20260416-2">
</head>
<body class="admin-panel-body">
<?php require __DIR__ . '/../includes/header.php'; ?>

<main class="admin-panel-main">
    <div class="admin-panel-container card-glass">
        <h1 class="admin-panel-page-title">Articles</h1>
        <p class="admin-panel-help">Liste de tous les articles (publiés et brouillons).</p>

        <?php if ($flashSuccessMessage !== ''): ?>
            <p class="admin-panel-alert admin-panel-alert-success" role="status"><?= htmlspecialchars($flashSuccessMessage, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <?php if ($flashErrorMessage !== ''): ?>
            <p class="admin-panel-alert admin-panel-alert-error" role="alert"><?= htmlspecialchars($flashErrorMessage, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <div class="admin-dashboard-actions">
            <a class="admin-auth-submit" href="<?= BASE_URL ?>/admin/articles/create.php">Ajouter un article</a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Publication</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articlesList as $articleRow): ?>
                        <?php
                        $articleId = (int) ($articleRow['id'] ?? 0);
                        $isPublished = (int) ($articleRow['is_published'] ?? 0) === 1;
                        $publishedAtRaw = $articleRow['published_at'] ?? null;
                        $publishedLabel = '';
                        if ($publishedAtRaw) {
                            $publishedLabel = date('d/m/Y H:i', strtotime((string) $publishedAtRaw));
                        } elseif ($isPublished) {
                            $publishedLabel = '—';
                        } else {
                            $publishedLabel = '—';
                        }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars((string) ($articleRow['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string) ($articleRow['category'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php if ($isPublished): ?>
                                    <span class="admin-badge admin-badge-published">Publié</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge-draft">Brouillon</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($publishedLabel, ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="<?= BASE_URL ?>/admin/articles/edit.php?id=<?= $articleId ?>">Modifier</a>
                                    <form
                                        class="admin-inline-form"
                                        method="post"
                                        action="<?= BASE_URL ?>/admin/articles/delete.php"
                                        onsubmit="return confirm('Supprimer cet article ? Cette action est définitive.');"
                                    >
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="article_id" value="<?= $articleId ?>">
                                        <button type="submit">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($articlesList === []): ?>
            <p class="admin-panel-help" style="margin-top:16px;">Aucun article en base pour le moment.</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
