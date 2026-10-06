<?php $brochureUrl = BASE_URL . '/assets/docs/brochure.pdf'; ?>
<section class="brochure-section">
  <div class="brochure-wrapper">
    <h1 class="brochure-title">Brochure de présentation SAHP</h1>

    <div class="brochure-viewer">
      <object data="<?= $brochureUrl ?>#view=FitH" type="application/pdf" aria-label="Brochure de présentation SAHP">
        <p class="brochure-fallback">
          Votre navigateur ne peut pas afficher le PDF ici.
          <a href="<?= $brochureUrl ?>" target="_blank" rel="noopener">Ouvrir la brochure</a>
        </p>
      </object>
    </div>

    <div class="brochure-actions">
      <a class="brochure-btn" href="<?= $brochureUrl ?>" target="_blank" rel="noopener">Ouvrir en plein écran</a>
      <a class="brochure-btn" href="<?= $brochureUrl ?>" download>Télécharger le PDF</a>
    </div>
  </div>
</section>
