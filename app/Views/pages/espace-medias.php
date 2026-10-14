<?php

declare(strict_types=1);

require_once APP_PATH . '/helpers/media_kit.php';

$isAdmin = km_is_admin();

$items = array_values(array_filter(km_sorted_items(), fn($it) => !empty($it['visible'])));
$groups = [];
foreach ($items as $it) {
    $key = isset(km_categories()[$it['category']]) ? $it['category'] : '_autres';
    $groups[$key][] = $it;
}
$ordered = [];
foreach (km_categories() as $k => $label) {
    if (!empty($groups[$k])) $ordered[$k] = $groups[$k];
}
if (!empty($groups['_autres'])) $ordered['_autres'] = $groups['_autres'];
?>
<div class="mk-page">
  <section class="mk-hero">
    <div class="mk-wrap">
      <div class="mk-eyebrow">Kit média</div>
      <h1><?= km_e(km_cfg('page_title')) ?></h1>
      <p><?= km_e(km_cfg('intro')) ?></p>
    </div>
    <svg class="mk-waves" viewBox="0 0 1440 70" preserveAspectRatio="none" aria-hidden="true">
      <path d="M0 38 C 240 70, 480 6, 720 30 S 1200 64, 1440 22 L1440 70 L0 70 Z" fill="#73C70F" opacity=".9"/>
      <path d="M0 52 C 260 22, 520 74, 780 46 S 1220 30, 1440 50 L1440 70 L0 70 Z" fill="#f4f7fb"/>
    </svg>
  </section>

  <div class="mk-content">
    <div class="mk-wrap">
      <?php if ($items): ?>
      <div class="mk-toolbar" role="search">
        <label class="mk-search">
          <?= km_icon('search') ?>
          <input type="search" id="mk-q" placeholder="Rechercher un fichier (ex. logo, QR, plaquette…)" aria-label="Rechercher un fichier" autocomplete="off">
        </label>
        <div class="mk-chips" role="group" aria-label="Filtrer par catégorie">
          <button class="mk-chip" type="button" data-filter="all" aria-pressed="true">Tout <small><?= count($items) ?></small></button>
          <?php foreach ($ordered as $k => $list): ?>
            <button class="mk-chip" type="button" data-filter="<?= km_e($k) ?>" aria-pressed="false"><?= km_e($k === '_autres' ? 'Autres' : km_cat_label($k)) ?> <small><?= count($list) ?></small></button>
          <?php endforeach; ?>
        </div>
      </div>

      <?php foreach ($ordered as $k => $list): $label = $k === '_autres' ? 'Autres' : km_cat_label($k); ?>
      <section class="mk-section" data-cat="<?= km_e($k) ?>">
        <div class="mk-section-head">
          <h2><?= km_e($label) ?> <small>(<?= count($list) ?>)</small></h2>
          <?php if (count($list) > 1 && $k !== '_autres'): ?>
            <a class="mk-btn mk-btn-ghost mk-btn-sm" href="<?= km_e(km_href(km_zip_path($k))) ?>"><?= km_icon('zip') ?> Tout télécharger (.zip)</a>
          <?php endif; ?>
        </div>
        <div class="mk-grid">
          <?php foreach ($list as $it):
              $thumb = km_thumb_url($it);
              $kind = km_preview_kind($it);
              $transparent = in_array($it['ext'], ['png', 'svg', 'gif', 'webp'], true) && empty($it['preview']);
              $cover = $it['ext'] === 'jpg' || !empty($it['preview']) || (!empty($it['thumb']) && in_array($it['ext'], ['pdf', 'ai'], true));
              $dl = km_href(km_download_path($it['id']));
              $view = km_href(km_download_path($it['id'], true));
              $search = mb_strtolower($it['title'] . ' ' . ($it['description'] ?? '') . ' ' . $it['ext'] . ' ' . $label);
          ?>
          <article class="mk-card" data-search="<?= km_e($search) ?>">
            <div class="mk-thumb<?= $transparent ? ' mk-checker' : '' ?><?= $cover ? ' mk-cover' : '' ?>">
              <?php if ($thumb): ?>
                <img src="<?= km_e($thumb) ?>" alt="Aperçu : <?= km_e($it['title']) ?>" loading="lazy" decoding="async">
              <?php else: ?>
                <?= km_file_icon($it['ext']) ?>
              <?php endif; ?>
              <?php if ($kind === 'image'): ?>
                <button class="mk-zoom" type="button" data-view="<?= km_e($view) ?>" data-title="<?= km_e($it['title']) ?>" data-dl="<?= km_e($dl) ?>" aria-label="Agrandir <?= km_e($it['title']) ?>"></button>
              <?php endif; ?>
            </div>
            <div class="mk-card-body">
              <h3><?= km_e($it['title']) ?></h3>
              <?php if (!empty($it['description'])): ?><p class="mk-desc"><?= nl2br(km_e($it['description'])) ?></p><?php endif; ?>
              <div class="mk-meta"><?= km_e(km_meta($it)) ?></div>
              <div class="mk-actions">
                <a class="mk-btn mk-btn-primary" href="<?= km_e($dl) ?>"><?= km_icon('download') ?> Télécharger</a>
                <?php if ($kind === 'image'): ?>
                  <button class="mk-btn mk-btn-ghost mk-btn-icon" type="button" data-view="<?= km_e($view) ?>" data-title="<?= km_e($it['title']) ?>" data-dl="<?= km_e($dl) ?>" title="Aperçu" aria-label="Aperçu"><?= km_icon('eye') ?></button>
                <?php elseif ($kind === 'pdf'): ?>
                  <a class="mk-btn mk-btn-ghost mk-btn-icon" href="<?= km_e($view) ?>" target="_blank" rel="noopener" title="Ouvrir le PDF" aria-label="Ouvrir le PDF"><?= km_icon('eye') ?></a>
                <?php endif; ?>
                <button class="mk-btn mk-btn-ghost mk-btn-icon" type="button" data-copy="<?= km_e(km_absolute_url(km_download_path($it['id']))) ?>" title="Copier le lien de téléchargement" aria-label="Copier le lien"><?= km_icon('link') ?></button>
              </div>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endforeach; ?>

      <div class="mk-empty" id="mk-no-result" hidden>
        <strong>Aucun fichier ne correspond à votre recherche.</strong>
        Essayez un autre mot, ou contactez-nous pour obtenir un format précis.
      </div>

      <?php else: ?>
      <div class="mk-empty">
        <strong>Les fichiers arrivent bientôt.</strong>
        <?php if ($isAdmin): ?><a href="<?= km_e(km_admin_href()) ?>">Ajouter les premiers fichiers →</a><?php else: ?>Revenez un peu plus tard.<?php endif; ?>
      </div>
      <?php endif; ?>

      <p class="mk-help">
        Besoin d'un autre format ?
        <a href="tel:+33<?= km_e(substr((string) preg_replace('/\D/', '', (string) km_cfg('contact_phone')), 1)) ?>"><?= km_e(km_cfg('contact_phone')) ?></a>
        · <a href="mailto:<?= km_e(km_cfg('contact_email')) ?>"><?= km_e(km_cfg('contact_email')) ?></a>
      </p>
    </div>
  </div>

  <dialog class="mk-lightbox" id="mk-lightbox" aria-label="Aperçu">
    <button class="mk-lb-close" type="button" data-close aria-label="Fermer">×</button>
    <figure>
      <div class="mk-lb-img"><img id="mk-lb-img" alt=""></div>
      <figcaption>
        <strong id="mk-lb-title"></strong>
        <a class="mk-btn mk-btn-primary mk-btn-auto" id="mk-lb-dl" href="#"><?= km_icon('download') ?> Télécharger</a>
      </figcaption>
    </figure>
  </dialog>
  <div class="mk-toast" id="mk-toast" role="status" aria-live="polite"></div>
</div>

<script src="<?= BASE_URL ?>/assets/js/espace-medias.js?v=<?= SAHP_ASSET_VERSION ?>" defer></script>
