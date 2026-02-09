<?php
/**
 * Script de test pour diagnostiquer les problèmes SMTP
 * À supprimer après résolution du problème
 */

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/helpers/email_templates.php';
require_once __DIR__ . '/includes/mailer.php';

echo "<h2>Test de configuration SMTP</h2>";

// Vérifier les variables d'environnement
echo "<h3>1. Variables d'environnement :</h3>";
echo "<pre>";
echo "SMTP_HOST: " . (isset($_ENV['SMTP_HOST']) ? $_ENV['SMTP_HOST'] : 'NON DÉFINI') . "\n";
echo "SMTP_PORT: " . (isset($_ENV['SMTP_PORT']) ? $_ENV['SMTP_PORT'] : 'NON DÉFINI') . "\n";
echo "SMTP_USER: " . (isset($_ENV['SMTP_USER']) ? $_ENV['SMTP_USER'] : 'NON DÉFINI') . "\n";
echo "SMTP_PASS: " . (isset($_ENV['SMTP_PASS']) ? str_repeat('*', strlen($_ENV['SMTP_PASS'] ?? '')) : 'NON DÉFINI') . "\n";
echo "SMTP_FROM: " . (isset($_ENV['SMTP_FROM']) ? $_ENV['SMTP_FROM'] : 'NON DÉFINI') . "\n";
echo "</pre>";

// Test d'envoi
echo "<h3>2. Test d'envoi d'email :</h3>";

// Créer un email HTML de test
$testData = [
    'name' => 'Test Utilisateur',
    'email' => 'test@example.com',
    'phone' => '01.23.45.67.89',
    'message' => 'Ceci est un email de test depuis le site SAHP. Si vous recevez ce message au format HTML, la configuration SMTP fonctionne correctement.'
];

$testHtmlBody = get_contact_email_html($testData);

$testResult = sahp_send_mail(
    "Test SMTP - SAHP",
    $testHtmlBody,
    null,
    null
);

if ($testResult) {
    echo "<p style='color: green;'><strong>✓ Succès !</strong> L'email a été envoyé avec succès.</p>";
} else {
    echo "<p style='color: red;'><strong>✗ Échec</strong> L'envoi a échoué. Consultez les logs PHP ci-dessous.</p>";
    echo "<p><strong>Note importante pour Gmail :</strong></p>";
    echo "<ul>";
    echo "<li>Si vous utilisez Gmail, vous devez créer un <strong>mot de passe d'application</strong> au lieu d'utiliser votre mot de passe normal</li>";
    echo "<li>Allez sur : <a href='https://myaccount.google.com/apppasswords' target='_blank'>https://myaccount.google.com/apppasswords</a></li>";
    echo "<li>Générez un mot de passe d'application et utilisez-le dans le fichier .env</li>";
    echo "</ul>";
}

echo "<h3>3. Logs d'erreur PHP :</h3>";
echo "<p>Vérifiez les logs d'erreur PHP dans :</p>";
echo "<ul>";
echo "<li>WAMP : C:\\wamp64\\logs\\php_error.log</li>";
echo "<li>Ou dans la configuration PHP de WAMP</li>";
echo "</ul>";

echo "<hr>";
echo "<p><small>Ce fichier doit être supprimé après résolution du problème pour des raisons de sécurité.</small></p>";
