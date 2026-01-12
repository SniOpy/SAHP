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

  $mail = new PHPMailer(true);

  try {
    // SMTP OVH
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USER'];
    $mail->Password = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = (int) $_ENV['SMTP_PORT'];

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
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $body;

    return $mail->send();

  } catch (Exception $e) {
    error_log('MAIL ERROR: ' . $mail->ErrorInfo);
    return false;
  }
}
