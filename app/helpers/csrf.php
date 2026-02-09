<?php
declare(strict_types=1);

/**
 * Protection CSRF pour les formulaires
 */

/**
 * Démarre la session si elle n'est pas déjà démarrée
 */
function ensure_session_started(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Génère un token CSRF et le stocke en session
 * 
 * @return string Le token CSRF
 */
function generate_csrf_token(): string {
    ensure_session_started();
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Valide un token CSRF
 * 
 * @param string $token Le token à valider
 * @return bool True si valide, false sinon
 */
function validate_csrf_token(string $token): bool {
    ensure_session_started();
    
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Régénère le token CSRF (après utilisation pour plus de sécurité)
 */
function regenerate_csrf_token(): void {
    ensure_session_started();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
