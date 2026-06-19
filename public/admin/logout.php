<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/includes/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

logoutAdminUser();
header('Location: ' . BASE_URL . '/admin/login.php');
exit;
