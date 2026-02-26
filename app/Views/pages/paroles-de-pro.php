<?php
require_once __DIR__ . '/../../helpers/blog.php';

$posts = blog_load_posts();

// Featured = dernier article publié (top SEO + logique)
$featured = $posts[0] ?? null;

// On affiche ensuite 3 cards (ou plus si tu veux)
$cards = array_slice($posts, 0, 6);
?>

<section id="parole-de-pro">

    <h2 class="parole-title">
        Les conseils de l’expert
    </h2>

    <!-- FEATURED (DYNAMIQUE) -->
    <?php if ($featured): ?>
        <div class="parole-featured">
            <div class="container-inner-blog">

                <div class="featured-text">
                    <span class="featured-label" style="color:#0f4c81;">PAROLES DE PRO</span>

                    <h3><?= blog_escape($featured['title']) ?></h3>

                    <p class="featured-intro">
                        <?= blog_escape($featured['excerpt'] ?? '') ?>
                    </p>

                    <p class="seo-text">
                        Conseils professionnels SAHP pour prévenir les bouchons, éviter les refoulements
                        et optimiser l’entretien de vos canalisations en Île-de-France.
                    </p>

                    <a href="<?= BASE_URL ?>/paroles-de-pro/<?= blog_escape($featured['slug']) ?>" class="article-link" style="display:inline-block;margin-top:14px;color:#0f4c81;font-weight:700;">
                        Lire l’article →
                    </a>
                </div>

                <div class="featured-visual">
                    <img
                        src="<?= BASE_URL ?>/assets/img/mascotte-blog.png"
                        alt="<?= blog_escape($featured['title']) ?>">
                </div>

            </div>
        </div>
    <?php endif; ?>

    <!-- ARTICLES BLOG (DYNAMIQUE) -->
    <div class="parole-articles">

        <?php foreach ($cards as $post): ?>
            <article class="article-card">
                <div class="article-image">
                    <img
                        src="<?= BASE_URL ?>/assets/img/blog/<?= blog_escape($post['cover_image'] ?? '') ?>"
                        alt="<?= blog_escape($post['title']) ?>"
                        loading="lazy">
                </div>

                <div class="article-content">
                    <span class="article-category"><?= blog_escape($post['category'] ?? 'Conseils') ?></span>

                    <h3><?= blog_escape($post['title']) ?></h3>

                    <p><?= blog_escape($post['excerpt'] ?? '') ?></p>

                    <a href="<?= BASE_URL ?>/paroles-de-pro/<?= blog_escape($post['slug']) ?>" class="article-link">
                        Lire l’article →
                    </a>
                </div>
            </article>
        <?php endforeach; ?>

    </div>

</section>