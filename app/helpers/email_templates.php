<?php
declare(strict_types=1);

/**
 * Templates HTML pour les emails
 */

/**
 * Génère le template HTML de base pour les emails
 */
function get_email_template(string $title, string $content): string {
    return '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(90deg, #4080b7 0%, #5ed0c2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -30px -30px 30px -30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            margin: 20px 0;
        }
        .info-row {
            margin: 15px 0;
            padding: 12px;
            background-color: #f8f9fa;
            border-left: 4px solid #4080b7;
            border-radius: 4px;
        }
        .info-label {
            font-weight: 700;
            color: #4080b7;
            display: inline-block;
            min-width: 120px;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333;
            word-break: break-word;
        }
        .message-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
            white-space: pre-wrap;
            font-family: inherit;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: #4080b7;
            color: white;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>SAHP Assainissement</h1>
        </div>
        <div class="content">
            ' . $content . '
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement depuis le site <strong>SAHP Assainissement</strong></p>
            <p>Intervention rapide • 24h/7j • 📞 01.76.24.28.84</p>
        </div>
    </div>
</body>
</html>';
}

/**
 * Template pour l'email de contact
 */
function get_contact_email_html(array $data): string {
    $content = '
        <h2 style="color: #4080b7; margin-top: 0;">Nouveau message de contact</h2>
        <p>Vous avez reçu un nouveau message via le formulaire de contact du site SAHP.</p>
        
        <div class="info-row">
            <div class="info-label">Nom :</div>
            <div class="info-value">' . htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8') . '</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Email :</div>
            <div class="info-value">
                <a href="mailto:' . htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8') . '" style="color: #4080b7;">
                    ' . htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8') . '
                </a>
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">
                <a href="tel:' . htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8') . '" style="color: #4080b7;">
                    ' . htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8') . '
                </a>
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Message :</div>
        </div>
        <div class="message-box">' . nl2br(htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8')) . '</div>
    ';
    
    return get_email_template('Nouveau message de contact - SAHP', $content);
}

/**
 * Template pour l'email de devis
 */
function get_devis_email_html(array $data): string {
    $content = '
        <h2 style="color: #4080b7; margin-top: 0;">Nouvelle demande de devis</h2>
        <p>Vous avez reçu une nouvelle demande de devis via le site SAHP.</p>
        
        <div class="info-row">
            <div class="info-label">Nom :</div>
            <div class="info-value">' . htmlspecialchars($data['nom'], ENT_QUOTES, 'UTF-8') . '</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Prénom :</div>
            <div class="info-value">' . htmlspecialchars($data['prenom'], ENT_QUOTES, 'UTF-8') . '</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">
                <a href="tel:' . htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8') . '" style="color: #4080b7;">
                    ' . htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8') . '
                </a>
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Prestation :</div>
            <div class="info-value">
                ' . htmlspecialchars($data['prestation'], ENT_QUOTES, 'UTF-8') . '
                <span class="badge">DEVIS</span>
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Sujet :</div>
            <div class="info-value">' . htmlspecialchars($data['sujet'], ENT_QUOTES, 'UTF-8') . '</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Message :</div>
        </div>
        <div class="message-box">' . nl2br(htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8')) . '</div>
    ';
    
    return get_email_template('Demande de devis - ' . htmlspecialchars($data['prestation'], ENT_QUOTES, 'UTF-8') . ' - SAHP', $content);
}
