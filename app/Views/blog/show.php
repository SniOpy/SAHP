<?php

declare(strict_types=1);

require_once __DIR__ . '/../../helpers/blog.php';

$post = $blogArticlePost ?? blog_find_post_by_slug($_GET['slug'] ?? '');

if (!$post) {
    http_response_code(404);
    require __DIR__ . '/../pages/404.php';
    exit;
}

$cover = '';
$coverRaw = $post['cover_image'] ?? '';
if ($coverRaw !== '') {
    $cover = blog_resolve_cover_image_url($coverRaw);
}
?>

<section class="pp-article">

  <!-- HERO -->
  <div class="pp-hero">
    <div class="pp-hero-inner">

      <div class="pp-hero-title">
        <h1><?= blog_escape($post['title'] ?? '') ?></h1>
        <p class="pp-hero-subtitle">
          Les conseils de l’expert SAHP • Prévention • Île-de-France
        </p>
      </div>

      <div class="pp-hero-bubble">
        <p><?= blog_escape($post['excerpt'] ?? '') ?></p>
      </div>

    </div>
  </div>

  <!-- WRAPPER GRID -->
  <div class="pp-wrapper">

    <!-- MAIN CONTENT -->
    <main class="pp-content">
      <div class="pp-card pp-back" style="margin-bottom:20px;">
        <a href="<?= BASE_URL ?>/paroles-de-pro">← Retour à tous les articles</a>
      </div>

      <?php if ($cover !== ''): ?>
        <div class="pp-cover">
          <img src="<?= blog_escape($cover) ?>" alt="<?= blog_escape($post['title'] ?? '') ?>">
        </div>
      <?php endif; ?>

      <div class="pp-body">
      <?php
        $content = $post['content'] ?? '';
        echo blog_prepare_content_html_for_output($content);
        blog_render_faq_schema_from_content($content);
        ?>


      </div>

    </main>

    <!-- SIDEBAR (vide tant que les cartes latérales sont commentées) -->
    <aside class="pp-sidebar" aria-label="Colonnes annexes">

      <!-- <div class="pp-card">
        <h3>Contactez SAHP<br><span>(Urgence 24/7)</span></h3>

        <a class="pp-side-btn" href="<?= BASE_URL ?>/contact">Demander un diagnostic</a>
        <a class="pp-side-btn outline" href="tel:+33176242884">📞 01 76 24 28 84</a>

        <div class="pp-mini">
          <p><strong>Zones :</strong> Île-de-France & alentours</p>
          <p><strong>Services :</strong> Débouchage • Curage • Inspection vidéo</p>
        </div>
      </div> -->

      <!-- <div class="pp-card">
        <h3>Conseil express</h3>
        <p class="pp-small">
          Un écoulement lent = un bouchon en formation. Un curage préventif évite
          une urgence et prolonge la durée de vie des canalisations.
        </p>
        <a class="pp-link" href="<?= BASE_URL ?>/curage">Découvrir le curage →</a>
      </div> -->

    </aside>

  </div>

</section>
