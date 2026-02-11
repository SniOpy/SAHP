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

    <!-- FORMULAIRE DE DEVIS -->
    <div class="devis-form">

      <h2>Demande de devis</h2>

      <?php if ($formMessage): ?>
        <div class="form-message <?= $formSuccess ? 'form-success' : 'form-error' ?>">
          <?= htmlspecialchars($formMessage, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <form action="" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-row">
          <input
            type="text"
            name="nom"
            placeholder="Nom"
            value="<?= isset($formData['nom']) ? htmlspecialchars($formData['nom'], ENT_QUOTES, 'UTF-8') : '' ?>"
            required
            <?= isset($formErrors['nom']) ? 'class="error"' : '' ?>>
          <?php if (isset($formErrors['nom'])): ?>
            <span class="error-message"><?= htmlspecialchars($formErrors['nom'], ENT_QUOTES, 'UTF-8') ?></span>
          <?php endif; ?>

          <input
            type="text"
            name="prenom"
            placeholder="Prénom"
            value="<?= isset($formData['prenom']) ? htmlspecialchars($formData['prenom'], ENT_QUOTES, 'UTF-8') : '' ?>"
            required
            <?= isset($formErrors['prenom']) ? 'class="error"' : '' ?>>
          <?php if (isset($formErrors['prenom'])): ?>
            <span class="error-message"><?= htmlspecialchars($formErrors['prenom'], ENT_QUOTES, 'UTF-8') ?></span>
          <?php endif; ?>
        </div>
        <div class="form-row">
          <input
            type="email"
            name="email"
            placeholder="Email"
            value="<?= isset($formData['email']) ? htmlspecialchars($formData['email'], ENT_QUOTES, 'UTF-8') : '' ?>"
            required
            <?= isset($formErrors['email']) ? 'class="error"' : '' ?>>
          <?php if (isset($formErrors['email'])): ?>
            <span class="error-message"><?= htmlspecialchars($formErrors['email'], ENT_QUOTES, 'UTF-8') ?></span>
          <?php endif; ?>

          <input
            type="tel"
            name="phone"
            placeholder="Téléphone"
            value="<?= isset($formData['phone']) ? htmlspecialchars($formData['phone'], ENT_QUOTES, 'UTF-8') : '' ?>"
            required
            <?= isset($formErrors['phone']) ? 'class="error"' : '' ?>>
          <?php if (isset($formErrors['phone'])): ?>
            <span class="error-message"><?= htmlspecialchars($formErrors['phone'], ENT_QUOTES, 'UTF-8') ?></span>
          <?php endif; ?>
        </div>




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

        <input
          type="text"
          name="sujet"
          placeholder="Sujet de votre demande"
          value="<?= isset($formData['sujet']) ? htmlspecialchars($formData['sujet'], ENT_QUOTES, 'UTF-8') : '' ?>"
          required
          <?= isset($formErrors['sujet']) ? 'class="error"' : '' ?>>
        <?php if (isset($formErrors['sujet'])): ?>
          <span class="error-message"><?= htmlspecialchars($formErrors['sujet'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>

        <textarea
          name="message"
          rows="5"
          placeholder="Décrivez votre besoin (urgence, localisation, problème rencontré…)"
          required
          <?= isset($formErrors['message']) ? 'class="error"' : '' ?>><?= isset($formData['message']) ? htmlspecialchars($formData['message'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
        <?php if (isset($formErrors['message'])): ?>
          <span class="error-message"><?= htmlspecialchars($formErrors['message'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>

        <button type="submit">Envoyer ma demande de devis</button>

      </form>

    </div>

    <!-- BLOC APPEL -->
    <div class="devis-call">

      <h3>Besoin d’une réponse rapide&nbsp;?</h3>

      <p>
        En cas de doute, d’urgence ou pour obtenir un avis immédiat,
        appelez directement notre équipe.
      </p>

      <a href="tel:+33176242884" class="call-phone">
        📞 01.76.24.28.84
      </a>

      <span class="call-note">
        Intervention rapide • 24h/7j
      </span>

    </div>

  </div>

</section>