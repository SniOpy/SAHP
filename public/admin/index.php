<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/includes/auth.php';

requireAdminLogin();

$adminPageTitle = 'Tableau de bord | SAHP Admin';
$adminUsername = (string) ($_SESSION[ADMIN_SESSION_USERNAME_KEY] ?? 'admin');
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
</head>
<body class="admin-panel-body">
<?php require __DIR__ . '/includes/header.php'; ?>

<main class="admin-panel-main">
    <div class="admin-panel-container card-glass">
        <h1 class="admin-panel-page-title">Bienvenue <?= htmlspecialchars($adminUsername, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="admin-dashboard-text">Tableau de bord admin. Vous pouvez créer des articles de blog en base de données.</p>

        <div class="admin-dashboard-actions">
            <a class="admin-auth-submit" href="<?= BASE_URL ?>/admin/articles/index.php">Liste des articles</a>
            <a class="admin-auth-submit" href="<?= BASE_URL ?>/admin/articles/create.php">Ajouter un article</a>
        </div>
    </div>
</main>
</body>
</html>
