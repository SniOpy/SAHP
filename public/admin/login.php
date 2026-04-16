<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/includes/auth.php';

if (isAdminAuthenticated()) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

$errorMessage = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $errorMessage = 'Identifiants invalides.';
    } else {
        try {
            $adminUser = findAdminUserByUsername($username);
            $isAuthenticated = $adminUser !== null && verifyAdminPassword($password, $adminUser['password_hash']);

            if ($isAuthenticated) {
                loginAdminUser($adminUser);
                header('Location: ' . BASE_URL . '/admin/index.php');
                exit;
            }
        } catch (Throwable $exception) {
            // Message volontairement generique pour ne pas exposer la configuration.
        }

        $errorMessage = 'Identifiants invalides.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion admin | SAHP Assainissement</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=20260209-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/admin-auth.css?v=20260413-1">
</head>

<body class="admin-auth-body">
    <main class="admin-auth-layout">
        <section class="admin-auth-card card-glass" aria-labelledby="admin-login-title">
            <div class="admin-auth-brand">
                <a href="<?= BASE_URL ?>/" aria-label="Retour au site SAHP">
                    <img src="<?= BASE_URL ?>/assets/img/sahp.png" alt="SAHP Assainissement">
                </a>
                <p>Espace administration</p>
            </div>

            <h1 id="admin-login-title">Connexion admin</h1>

            <?php if ($errorMessage !== ''): ?>
                <p class="admin-auth-error" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/admin/login.php" class="admin-auth-form" novalidate>
                <div class="admin-field-group">
                    <label for="username">Identifiant</label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        autocomplete="username"
                        value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>

                <div class="admin-field-group">
                    <label for="password">Mot de passe</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required>
                </div>

                <button type="submit" class="admin-auth-submit">Se connecter</button>
            </form>
        </section>
    </main>
</body>

</html>