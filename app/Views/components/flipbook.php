<?php

declare(strict_types=1);

/** @var string $flipbookTitle */
/** @var string $flipbookPdf */
/** @var bool $flipbookSingle */

$esc = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<section
  class="brochure-section"
  data-flipbook
  data-pdf="<?= $esc($flipbookPdf) ?>"
  <?= $flipbookSingle ? 'data-single="1"' : '' ?>>
  <div class="brochure-wrapper">
    <h1 class="brochure-title"><?= $esc($flipbookTitle) ?></h1>

    <div class="brochure-stage" data-flipbook-stage>
      <div class="brochure-loader" data-flipbook-loader role="status" aria-live="polite">
        <span class="brochure-spinner" aria-hidden="true"></span>
        <span class="brochure-loader-text">Chargement… <span data-flipbook-progress>0%</span></span>
      </div>

      <div class="brochure-book" data-flipbook-book aria-label="<?= $esc($flipbookTitle) ?>"></div>

      <div class="brochure-fallback" data-flipbook-fallback hidden>
        <p>Impossible d'afficher le document ici.</p>
        <a href="<?= $esc($flipbookPdf) ?>" target="_blank" rel="noopener">Ouvrir le document</a>
      </div>
      <noscript>
        <div class="brochure-fallback">
          <p>Activez JavaScript pour feuilleter le document.</p>
          <a href="<?= $esc($flipbookPdf) ?>" target="_blank" rel="noopener">Ouvrir le document</a>
        </div>
      </noscript>

      <div class="brochure-toolbar" data-flipbook-toolbar hidden>
        <button type="button" class="brochure-tool" data-flipbook-prev aria-label="Page précédente">
          <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M15 18l-6-6 6-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </button>
        <span class="brochure-counter" data-flipbook-counter aria-live="polite">1 / 1</span>
        <button type="button" class="brochure-tool" data-flipbook-next aria-label="Page suivante">
          <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M9 18l6-6-6-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </button>
        <span class="brochure-toolbar-sep" aria-hidden="true"></span>
        <button type="button" class="brochure-tool" data-flipbook-fullscreen aria-label="Plein écran">
          <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M4 9V4h5M20 9V4h-5M4 15v5h5M20 15v5h-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </button>
        <a class="brochure-tool" href="<?= $esc($flipbookPdf) ?>" download aria-label="Télécharger le PDF">
          <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M12 4v11m0 0l-4.5-4.5M12 15l4.5-4.5M5 20h14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </a>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.js" defer></script>
<script type="module" src="<?= BASE_URL ?>/assets/js/flipbook.js?v=<?= SAHP_ASSET_VERSION ?>"></script>
