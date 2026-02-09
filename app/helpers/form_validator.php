<?php
declare(strict_types=1);

/**
 * Validation et sanitization des données de formulaire
 */

/**
 * Valide un numéro de téléphone français
 * Accepte les formats: 01.23.45.67.89, 0123456789, +33 1 23 45 67 89, 01-23-45-67-89
 * 
 * @param string $phone Le numéro de téléphone à valider
 * @return bool True si valide, false sinon
 */
function validate_french_phone(string $phone): bool {
    // Nettoyer le numéro (supprimer espaces, points, tirets)
    $cleaned = preg_replace('/[\s.\-]/', '', $phone);
    
    // Regex pour numéros français
    // Format: 0[1-9] suivi de 8 chiffres OU +33[1-9] suivi de 8 chiffres OU 0033[1-9] suivi de 8 chiffres
    $pattern = '/^(?:(?:\+|00)33|0)[1-9]\d{8}$/';
    
    return preg_match($pattern, $cleaned) === 1;
}

/**
 * Nettoie une chaîne de caractères pour éviter XSS et SQL injection
 * 
 * @param string $input La chaîne à nettoyer
 * @return string La chaîne nettoyée
 */
function sanitize_input(string $input): string {
    // Supprimer les espaces en début/fin
    $input = trim($input);
    
    // Supprimer les slashes si magic_quotes est activé (anciennes versions PHP)
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
        $input = stripslashes($input);
    }
    
    // Protection XSS avec htmlspecialchars
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    
    return $input;
}

/**
 * Nettoie une chaîne pour l'utiliser dans les emails HTML
 * Nettoie sans encoder HTML (les templates géreront l'encodage)
 * 
 * @param string $input La chaîne à nettoyer
 * @return string La chaîne nettoyée (UTF-8, sans HTML encodé)
 */
function sanitize_for_email(string $input): string {
    // Supprimer les espaces en début/fin
    $input = trim($input);
    
    // Supprimer les slashes si magic_quotes est activé
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
        $input = stripslashes($input);
    }
    
    // Décoder les entités HTML si elles existent déjà (éviter double encodage)
    $input = html_entity_decode($input, ENT_QUOTES, 'UTF-8');
    
    // Nettoyer les caractères de contrôle mais garder UTF-8 intact
    $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
    
    return $input;
}

/**
 * Valide une adresse email
 * 
 * @param string $email L'email à valider
 * @return bool True si valide, false sinon
 */
function validate_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valide un texte (nom, prénom, sujet)
 * 
 * @param string $text Le texte à valider
 * @param int $minLength Longueur minimale (défaut: 2)
 * @param int $maxLength Longueur maximale (défaut: 100)
 * @return array ['valid' => bool, 'error' => string|null]
 */
function validate_text(string $text, int $minLength = 2, int $maxLength = 100): array {
    $text = trim($text);
    
    if (empty($text)) {
        return ['valid' => false, 'error' => 'Ce champ est obligatoire'];
    }
    
    if (mb_strlen($text) < $minLength) {
        return ['valid' => false, 'error' => "Ce champ doit contenir au moins {$minLength} caractères"];
    }
    
    if (mb_strlen($text) > $maxLength) {
        return ['valid' => false, 'error' => "Ce champ ne peut pas dépasser {$maxLength} caractères"];
    }
    
    // Vérifier qu'il n'y a pas que des espaces
    if (preg_match('/^\s+$/', $text)) {
        return ['valid' => false, 'error' => 'Ce champ ne peut pas contenir uniquement des espaces'];
    }
    
    return ['valid' => true, 'error' => null];
}

/**
 * Valide un message (textarea)
 * 
 * @param string $message Le message à valider
 * @param int $minLength Longueur minimale (défaut: 10)
 * @param int $maxLength Longueur maximale (défaut: 2000)
 * @return array ['valid' => bool, 'error' => string|null]
 */
function validate_message(string $message, int $minLength = 10, int $maxLength = 2000): array {
    $message = trim($message);
    
    if (empty($message)) {
        return ['valid' => false, 'error' => 'Ce champ est obligatoire'];
    }
    
    if (mb_strlen($message) < $minLength) {
        return ['valid' => false, 'error' => "Le message doit contenir au moins {$minLength} caractères"];
    }
    
    if (mb_strlen($message) > $maxLength) {
        return ['valid' => false, 'error' => "Le message ne peut pas dépasser {$maxLength} caractères"];
    }
    
    return ['valid' => true, 'error' => null];
}

/**
 * Valide une option de sélection (select)
 * 
 * @param string $value La valeur sélectionnée
 * @param array $allowedValues Liste des valeurs autorisées
 * @return array ['valid' => bool, 'error' => string|null]
 */
function validate_select(string $value, array $allowedValues): array {
    if (empty($value)) {
        return ['valid' => false, 'error' => 'Veuillez sélectionner une option'];
    }
    
    if (!in_array($value, $allowedValues, true)) {
        return ['valid' => false, 'error' => 'Valeur non autorisée'];
    }
    
    return ['valid' => true, 'error' => null];
}
