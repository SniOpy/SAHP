<?php
// Charger les helpers nécessaires
require_once __DIR__ . '/../../helpers/form_handler.php';
require_once __DIR__ . '/../../helpers/csrf.php';

// Traiter le formulaire si soumis
$formResult = handle_devis_form();
$formData = $formResult['data'] ?? [];
$formErrors = $formResult['errors'] ?? [];
$formMessage = $formResult['message'] ?? '';
$formSuccess = $formResult['success'] ?? false;

// Générer le token CSRF
$csrfToken = generate_csrf_token();

// Liste des prestations pour le select
$prestations = [
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
?>

<section id="devis-sahp">

  <div class="devis-container">

    <!-- EN-TÊTE LANDING PAGE -->
    <header class="devis-header">
      <span class="devis-badge">Devis gratuit &amp; sans engagement</span>
      <h1>Recevez votre devis assainissement en quelques minutes</h1>
      <p class="devis-subtitle">
        Un formulaire ultra-rapide, une réponse claire. Décrivez votre besoin en
        quelques secondes&nbsp;: notre équipe vous recontacte avec une solution adaptée.
      </p>
    </header>

    <div class="devis-grid">

      <!-- FORMULAIRE DE DEVIS (minimal, orienté conversion) -->
      <div class="devis-form">

        <h2>Demande de devis <span>en 30 secondes</span></h2>

        <?php if ($formMessage): ?>
          <div class="form-message <?= $formSuccess ? 'form-success' : 'form-error' ?>">
            <?= htmlspecialchars($formMessage, ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <form action="" method="post" id=devis-form>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

          <div class="devis-field">
            <input
              type="text"
              name="nom"
              autocomplete="name"
              placeholder="Votre nom"
              value="<?= isset($formData['nom']) ? htmlspecialchars($formData['nom'], ENT_QUOTES, 'UTF-8') : '' ?>"
              required
              <?= isset($formErrors['nom']) ? 'class="error"' : '' ?>>
            <?php if (isset($formErrors['nom'])): ?>
              <span class="error-message"><?= htmlspecialchars($formErrors['nom'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>

          <div class="devis-field">
            <input
              type="tel"
              name="phone"
              autocomplete="tel"
              inputmode="tel"
              placeholder="Votre téléphone"
              value="<?= isset($formData['phone']) ? htmlspecialchars($formData['phone'], ENT_QUOTES, 'UTF-8') : '' ?>"
              required
              <?= isset($formErrors['phone']) ? 'class="error"' : '' ?>>
            <?php if (isset($formErrors['phone'])): ?>
              <span class="error-message"><?= htmlspecialchars($formErrors['phone'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>

          <div class="devis-field">
            <select name="prestation" required <?= isset($formErrors['prestation']) ? 'class="error"' : '' ?>>
              <option value="">Type de prestation</option>
              <?php foreach ($prestations as $prest): ?>
                <option
                  value="<?= htmlspecialchars($prest, ENT_QUOTES, 'UTF-8') ?>"
                  <?= (isset($formData['prestation']) && $formData['prestation'] === $prest) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($prest, ENT_QUOTES, 'UTF-8') ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($formErrors['prestation'])): ?>
              <span class="error-message"><?= htmlspecialchars($formErrors['prestation'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>

          <div class="devis-field">
            <textarea
              name="message"
              rows="3"
              placeholder="Décrivez brièvement votre besoin (optionnel)"
              <?= isset($formErrors['message']) ? 'class="error"' : '' ?>><?= isset($formData['message']) ? htmlspecialchars($formData['message'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
            <?php if (isset($formErrors['message'])): ?>
              <span class="error-message"><?= htmlspecialchars($formErrors['message'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>

          <button type="submit">Obtenir mon devis gratuit</button>

          <p class="devis-form-reassure">
            🔒 Vos informations restent confidentielles. Aucun engagement de votre part.
          </p>
        </form>

      </div>

      <!-- BLOC APPEL (alternative de conversion) -->
      <aside class="devis-call">

        <span class="devis-call-badge">Réponse immédiate</span>

        <h3>Vous préférez parler à un expert&nbsp;?</h3>

        <p>
          En cas d’urgence ou pour un avis immédiat, appelez directement notre
          équipe. On vous répond et on vous oriente tout de suite.
        </p>

        <a href="tel:+33176242884" class="call-phone">
          📞 01.76.24.28.84
        </a>

        <span class="call-note">Intervention rapide • 24h/7j</span>

        <ul class="devis-call-points">
          <li><span class="devis-call-check">✓</span> Conseil gratuit par téléphone</li>
          <li><span class="devis-call-check">✓</span> Diagnostic et orientation immédiate</li>
          <li><span class="devis-call-check">✓</span> Intervention d’urgence en Île-de-France</li>
        </ul>

      </aside>

    </div>

    <ul class="devis-trust">
      <li><span class="devis-trust-icon">✓</span> Réponse rapide</li>
      <li><span class="devis-trust-icon">✓</span> Sans engagement</li>
      <li><span class="devis-trust-icon">✓</span> Intervention 24h/7j</li>
      <li><span class="devis-trust-icon">✓</span> Particuliers &amp; pros</li>
    </ul>

  </div>

</section>