<?php
require_once __DIR__ . '/../../helpers/blog.php';

$slug = $_GET['slug'] ?? '';
$post = blog_find_post_by_slug($slug);

if (!$post) {
  http_response_code(404);
  require __DIR__ . '/../pages/404.php';
  exit;
}

$title = ($post['title'] ?? "Paroles de Pros") . " | SAHP Assainissement";
$meta_description = substr(strip_tags($post['excerpt'] ?? ""), 0, 155);
$canonical = BASE_URL . "/paroles-de-pro/" . ($post['slug'] ?? "");

$cover = !empty($post['cover_image'])
  ? BASE_URL . "/assets/img/blog/" . blog_escape($post['cover_image'])
  : "";
?>

<section class="pp-article">

  <!-- HERO -->
  <div class="pp-hero">
    <div class="pp-hero-inner">

      <div class="pp-hero-title">
        <h1><?= blog_escape($post['title'] ?? "") ?></h1>
        <p class="pp-hero-subtitle">
          Les conseils de l’expert SAHP • Prévention • Île-de-France
        </p>
      </div>

      <div class="pp-hero-bubble">
        <p><?= blog_escape($post['excerpt'] ?? "") ?></p>
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

      <?php if (!empty($cover)): ?>
        <div class="pp-cover">
          <img src="<?= $cover ?>" alt="<?= blog_escape($post['title'] ?? "") ?>">
        </div>
      <?php endif; ?>

      <div class="pp-body">
      <?php
        $content = $post['content'] ?? '';
        $content = str_replace('src="/assets/', 'src="' . BASE_URL . '/assets/', $content);
        $content = str_replace('href="/', 'href="' . BASE_URL . '/', $content);
        echo $content;
        ?>


      </div>

    </main>

    <!-- SIDEBAR -->
    <aside class="pp-sidebar">

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

      

    <!-- </aside> -->

  </div>

</section>
