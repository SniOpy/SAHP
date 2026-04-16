<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/includes/auth.php';

requireAdminLogin();

$adminUsername = (string) ($_SESSION[ADMIN_SESSION_USERNAME_KEY] ?? 'admin');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard admin | SAHP Assainissement</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=20260209-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-auth.css?v=20260413-1">
</head>
<body class="admin-auth-body">
<main class="admin-auth-layout">
    <section class="admin-auth-card card-glass">
        <div class="admin-auth-brand">
            <a href="<?= BASE_URL ?>/" aria-label="Retour au site SAHP">
                <img src="<?= BASE_URL ?>/assets/img/sahp.png" alt="SAHP Assainissement">
            </a>
            <p>Espace administration</p>
        </div>

        <h1>Bienvenue <?= htmlspecialchars($adminUsername, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="admin-dashboard-text">La connexion admin est active. Le futur panel sera construit a partir de cette base.</p>

        <a class="admin-auth-submit admin-logout-link" href="<?= BASE_URL ?>/admin/logout.php">Se deconnecter</a>
    </section>
</main>
</body>
</html>
