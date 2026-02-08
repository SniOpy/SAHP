<?php
declare(strict_types=1);

require_once __DIR__ . '/form_validator.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/email_templates.php';
require_once __DIR__ . '/../../includes/mailer.php';

/**
 * Traite le formulaire de contact
 * 
 * @return array ['success' => bool, 'message' => string, 'errors' => array]
 */
function handle_contact_form(): array {
    // Vérifier que c'est une requête POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return ['success' => false, 'message' => '', 'errors' => []];
    }
    
    // Valider le token CSRF
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($csrfToken)) {
        return [
            'success' => false,
            'message' => 'Erreur de sécurité. Veuillez réessayer.',
            'errors' => ['csrf' => 'Token CSRF invalide']
        ];
    }
    
    $errors = [];
    $data = [];
    
    // Récupérer et valider les données
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validation du nom
    $nameValidation = validate_text($name, 2, 100);
    if (!$nameValidation['valid']) {
        $errors['name'] = $nameValidation['error'];
    } else {
        $data['name'] = sanitize_input($name);
    }
    
    // Validation de l'email
    if (empty($email)) {
        $errors['email'] = 'L\'email est obligatoire';
    } elseif (!validate_email($email)) {
        $errors['email'] = 'Format d\'email invalide';
    } else {
        $data['email'] = filter_var($email, FILTER_SANITIZE_EMAIL);
    }
    
    // Validation du téléphone
    if (empty($phone)) {
        $errors['phone'] = 'Le téléphone est obligatoire';
    } elseif (!validate_french_phone($phone)) {
        $errors['phone'] = 'Format de téléphone invalide. Utilisez un numéro français (ex: 01.23.45.67.89)';
    } else {
        $data['phone'] = sanitize_input($phone);
    }
    
    // Validation du message
    $messageValidation = validate_message($message, 10, 2000);
    if (!$messageValidation['valid']) {
        $errors['message'] = $messageValidation['error'];
    } else {
        $data['message'] = sanitize_input($message);
    }
    
    // Si erreurs, retourner les erreurs
    if (!empty($errors)) {
        return [
            'success' => false,
            'message' => 'Veuillez corriger les erreurs ci-dessous.',
            'errors' => $errors,
            'data' => $data
        ];
    }
    
    // Préparer l'email HTML
    $subject = "Nouveau message de contact - SAHP";
    $htmlBody = get_contact_email_html($data);
    
    // Envoyer l'email
    $emailSent = sahp_send_mail(
        $subject,
        $htmlBody,
        $data['email'],
        $data['name']
    );
    
    if (!$emailSent) {
        // En mode développement, afficher plus de détails
        $errorMessage = 'Une erreur est survenue lors de l\'envoi. Veuillez réessayer plus tard.';
        if (defined('APP_ENV') && APP_ENV === true) {
            $errorMessage .= ' Consultez les logs PHP pour plus de détails.';
        }
        return [
            'success' => false,
            'message' => $errorMessage,
            'errors' => []
        ];
    }
    
    // Régénérer le token CSRF après succès
    regenerate_csrf_token();
    
    return [
        'success' => true,
        'message' => 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.',
        'errors' => []
    ];
}

/**
 * Traite le formulaire de devis
 * 
 * @return array ['success' => bool, 'message' => string, 'errors' => array]
 */
function handle_devis_form(): array {
    // Vérifier que c'est une requête POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return ['success' => false, 'message' => '', 'errors' => []];
    }
    
    // Valider le token CSRF
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($csrfToken)) {
        return [
            'success' => false,
            'message' => 'Erreur de sécurité. Veuillez réessayer.',
            'errors' => ['csrf' => 'Token CSRF invalide']
        ];
    }
    
    $errors = [];
    $data = [];
    
    // Liste des prestations autorisées
    $allowedPrestations = [
        'Débouchage de canalisation',
        'Curage préventif',
        'Curage haute pression',
        'Inspection caméra',
        'Vidange fosse septique',
        'Pompage eaux usées / pluviales',
        'Dégorgement WC / évier / douche',
        'Recherche de bouchon',
        'Assainissement collectif',
        'Assainissement individuel',
        'Urgence assainissement',
        'Autre'
    ];
    
    // Récupérer et valider les données
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $prestation = $_POST['prestation'] ?? '';
    $sujet = $_POST['sujet'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validation du nom
    $nomValidation = validate_text($nom, 2, 100);
    if (!$nomValidation['valid']) {
        $errors['nom'] = $nomValidation['error'];
    } else {
        $data['nom'] = sanitize_input($nom);
    }
    
    // Validation du prénom
    $prenomValidation = validate_text($prenom, 2, 100);
    if (!$prenomValidation['valid']) {
        $errors['prenom'] = $prenomValidation['error'];
    } else {
        $data['prenom'] = sanitize_input($prenom);
    }
    
    // Validation du téléphone
    if (empty($phone)) {
        $errors['phone'] = 'Le téléphone est obligatoire';
    } elseif (!validate_french_phone($phone)) {
        $errors['phone'] = 'Format de téléphone invalide. Utilisez un numéro français (ex: 01.23.45.67.89)';
    } else {
        $data['phone'] = sanitize_input($phone);
    }
    
    // Validation de la prestation
    $prestationValidation = validate_select($prestation, $allowedPrestations);
    if (!$prestationValidation['valid']) {
        $errors['prestation'] = $prestationValidation['error'];
    } else {
        $data['prestation'] = sanitize_input($prestation);
    }
    
    // Validation du sujet
    $sujetValidation = validate_text($sujet, 5, 200);
    if (!$sujetValidation['valid']) {
        $errors['sujet'] = $sujetValidation['error'];
    } else {
        $data['sujet'] = sanitize_input($sujet);
    }
    
    // Validation du message
    $messageValidation = validate_message($message, 10, 2000);
    if (!$messageValidation['valid']) {
        $errors['message'] = $messageValidation['error'];
    } else {
        $data['message'] = sanitize_input($message);
    }
    
    // Si erreurs, retourner les erreurs
    if (!empty($errors)) {
        return [
            'success' => false,
            'message' => 'Veuillez corriger les erreurs ci-dessous.',
            'errors' => $errors,
            'data' => $data
        ];
    }
    
    // Préparer l'email HTML
    $subject = "Demande de devis - " . $data['prestation'] . " - SAHP";
    $htmlBody = get_devis_email_html($data);
    
    // Envoyer l'email
    $emailSent = sahp_send_mail(
        $subject,
        $htmlBody,
        null, // Pas d'email de réponse pour le devis
        $data['nom'] . ' ' . $data['prenom']
    );
    
    if (!$emailSent) {
        // En mode développement, afficher plus de détails
        $errorMessage = 'Une erreur est survenue lors de l\'envoi. Veuillez réessayer plus tard.';
        if (defined('APP_ENV') && APP_ENV === true) {
            $errorMessage .= ' Consultez les logs PHP pour plus de détails.';
        }
        return [
            'success' => false,
            'message' => $errorMessage,
            'errors' => []
        ];
    }
    
    // Régénérer le token CSRF après succès
    regenerate_csrf_token();
    
    return [
        'success' => true,
        'message' => 'Votre demande de devis a été envoyée avec succès. Nous vous contacterons dans les plus brefs délais.',
        'errors' => []
    ];
}
