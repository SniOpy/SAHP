<?php
// Charger les helpers nécessaires
require_once __DIR__ . '/../../helpers/form_handler.php';
require_once __DIR__ . '/../../helpers/csrf.php';

// Traiter le formulaire si soumis
$formResult = handle_contact_form();
$formData = $formResult['data'] ?? [];
$formErrors = $formResult['errors'] ?? [];
$formMessage = $formResult['message'] ?? '';
$formSuccess = $formResult['success'] ?? false;

// Générer le token CSRF
$csrfToken = generate_csrf_token();
?>

<section id="contact-sahp">

  <div class="contact-container">

    <!-- FORMULAIRE -->
    <div class="contact-form">
      <h2>Contactez SAHP<br><span>(Urgence 24/7)</span></h2>

      <?php if ($formMessage): ?>
        <div class="form-message <?= $formSuccess ? 'form-success' : 'form-error' ?>">
          <?= htmlspecialchars($formMessage, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <form action="" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <input
          type="text"
          name="name"
          placeholder="Nom"
          value="<?= isset($formData['name']) ? htmlspecialchars($formData['name'], ENT_QUOTES, 'UTF-8') : '' ?>"
          required
          <?= isset($formErrors['name']) ? 'class="error"' : '' ?>>
        <?php if (isset($formErrors['name'])): ?>
          <span class="error-message"><?= htmlspecialchars($formErrors['name'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>

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

        <textarea
          name="message"
          rows="4"
          placeholder="Message"
          required
          <?= isset($formErrors['message']) ? 'class="error"' : '' ?>><?= isset($formData['message']) ? htmlspecialchars($formData['message'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
        <?php if (isset($formErrors['message'])): ?>
          <span class="error-message"><?= htmlspecialchars($formErrors['message'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>

        <button type="submit">Envoyer</button>
      </form>
    </div>

    <!-- INFOS CONTACT -->
    <div class="contact-infos">

      <div class="contact-mascotte">
        <img src="<?= BASE_URL ?>/assets/img/contact/mascotte-contact.png"
          alt="Mascotte SAHP contact urgence">
      </div>

      <div class="contact-details">
        <a href="tel:0176242884" class="phone">01.76.24.28.84</a>
        <p class="email">
          <a href="mailto:contact@sahp-idf.fr">
            contact@sahp-idf.fr
          </a>
        </p>
        <p class="hotline">📞 Hotlines : 24h/7j</p>
      </div>

      <div class="contact-zone">
        <h4>Zones d’intervention</h4>
        <img src="<?= BASE_URL ?>/assets/img/intervention.png"
          alt="Zones d’intervention SAHP">
      </div>

    </div>

  </div>

</section>