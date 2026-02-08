<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config.php';

function sahp_send_mail(
  string $subject,
  string $body,
  ?string $replyToEmail = null,
  ?string $replyToName = null
): bool {

  // Vérifier que les variables d'environnement sont chargées
  if (!isset($_ENV['SMTP_HOST']) || !isset($_ENV['SMTP_USER']) || !isset($_ENV['SMTP_PASS'])) {
    error_log('MAIL ERROR: Variables SMTP manquantes dans $_ENV');
    return false;
  }

  $mail = new PHPMailer(true);

  try {
    // Activer le mode debug en développement (désactiver en production)
    if (defined('APP_ENV') && APP_ENV === true) {
      $mail->SMTPDebug = 2; // Affiche les détails de connexion
      $mail->Debugoutput = function($str, $level) {
        error_log("PHPMailer Debug ($level): $str");
      };
    }

    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USER'];
    $mail->Password = $_ENV['SMTP_PASS'];
    $mail->Port = (int) $_ENV['SMTP_PORT'];
    
    // Port 465 nécessite SSL, port 587 nécessite STARTTLS
    if ($mail->Port === 465) {
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL pour port 465
    } else {
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // STARTTLS pour port 587
    }

    // Options supplémentaires pour Gmail
    if (strpos($_ENV['SMTP_HOST'], 'gmail.com') !== false) {
      $mail->SMTPOptions = array(
        'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      );
    }

    // Encodage
    $mail->CharSet = 'UTF-8';

    // Expéditeur
    $mail->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_NAME']);
    $mail->addAddress($_ENV['SMTP_FROM']);

    // Reply-to (client)
    if ($replyToEmail) {
      $mail->addReplyTo($replyToEmail, $replyToName ?: $replyToEmail);
    }

    // Contenu
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    
    // Version texte alternative pour les clients email qui ne supportent pas HTML
    // Convertir le HTML en texte lisible
    $textBody = strip_tags($body);
    $textBody = html_entity_decode($textBody, ENT_QUOTES, 'UTF-8');
    $textBody = preg_replace('/\s+/', ' ', $textBody); // Remplacer les espaces multiples
    $textBody = trim($textBody);
    $mail->AltBody = $textBody;

    $result = $mail->send();
    
    if (!$result) {
      error_log('MAIL ERROR: Échec de l\'envoi - ' . $mail->ErrorInfo);
    }
    
    return $result;

  } catch (Exception $e) {
    error_log('MAIL ERROR Exception: ' . $e->getMessage());
    error_log('MAIL ERROR Info: ' . $mail->ErrorInfo);
    return false;
  }
}
