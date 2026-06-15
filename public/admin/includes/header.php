<?php

declare(strict_types=1);

if (!function_exists('isAdminAuthenticated') || !isAdminAuthenticated()) {
    return;
}

$adminHeaderDisplayName = htmlspecialchars(
    (string) ($_SESSION[ADMIN_SESSION_USERNAME_KEY] ?? ''),
    ENT_QUOTES,
    'UTF-8'
);
?>
<header class="admin-panel-header card-glass" role="banner">
    <div class="admin-panel-header-inner">
        <a class="admin-panel-brand" href="<?= BASE_URL ?>/admin/index.php">
            <img src="<?= BASE_URL ?>/assets/img/sahp.png" alt="Logo SAHP Assainissement" width="120" height="auto" class="admin-panel-brand-logo">
            <span class="admin-panel-brand-text">SAHP Admin</span>
        </a>

        <nav class="admin-panel-nav" aria-label="Navigation administration">
            <a href="<?= BASE_URL ?>/admin/index.php">Tableau de bord</a>
            <a href="<?= BASE_URL ?>/admin/articles/index.php">Articles</a>
            <a href="<?= BASE_URL ?>/admin/articles/create.php">Ajouter un article</a>
        </nav>

        <div class="admin-panel-header-actions">
            <span class="admin-panel-user" title="Utilisateur connecte"><?= $adminHeaderDisplayName ?></span>
            <a class="admin-panel-logout" href="<?= BASE_URL ?>/admin/logout.php">Déconnexion</a>
        </div>
    </div>
</header>
