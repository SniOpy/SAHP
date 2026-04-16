<?php
require_once __DIR__ . '/../../helpers/blog.php';

$posts = blog_load_posts();

$title = "Paroles de Pros | SAHP";
$meta_description = "Conseils d’assainissement, curage, débouchage et dépannage. Articles pro SAHP en Île-de-France.";
$canonical = BASE_URL . "/paroles-de-pro";
?>

<section class="blog-page">
  <div class="blog-container">

    <h1>Paroles de Pros</h1>
    <p class="blog-intro">
      Conseils pro, prévention, dépannage : tout ce qu’il faut savoir sur l’assainissement.
    </p>

    <?php if (empty($posts)): ?>
      <p class="blog-intro" style="text-align:center;">Aucun article pour le moment.</p>
    <?php endif; ?>

    <div class="blog-grid">
      <?php foreach ($posts as $post): ?>
        <?php $coverImageUrl = blog_resolve_cover_image_url_for_card($post['cover_image'] ?? ''); ?>
        <article class="blog-card">
          <a href="<?= BASE_URL ?>/paroles-de-pro/<?= blog_escape($post['slug']) ?>">
            <img
                src="<?= blog_escape($coverImageUrl) ?>"
                alt="<?= blog_escape($post['title']) ?>"
                loading="lazy"
              />

            <div class="blog-card-content">
              <span class="blog-meta">
                <?= blog_escape($post['category'] ?? "Conseils") ?> · <?= blog_escape($post['published_at'] ?? "") ?>
              </span>

              <h2><?= blog_escape($post['title']) ?></h2>
              <p><?= blog_escape($post['excerpt'] ?? "") ?></p>

              <span class="blog-readmore">Lire l’article →</span>
            </div>
          </a>
        </article>
      <?php endforeach; ?>
    </div>

  </div>
</section>
