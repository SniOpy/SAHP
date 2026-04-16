<?php

declare(strict_types=1);

/**
 * Token CSRF simple pour les formulaires admin (evite les soumissions forgees).
 */
function ensureAdminCsrfToken(): string
{
    if (empty($_SESSION['admin_csrf_token']) || !is_string($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['admin_csrf_token'];
}

/**
 * Verifie le token CSRF envoye en POST.
 */
function verifyAdminCsrfTokenFromPost(): bool
{
    $submittedToken = $_POST['csrf_token'] ?? '';
    $storedToken = $_SESSION['admin_csrf_token'] ?? '';

    if (!is_string($submittedToken) || $storedToken === '' || !is_string($storedToken)) {
        return false;
    }

    return hash_equals($storedToken, $submittedToken);
}
