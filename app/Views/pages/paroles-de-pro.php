<?php
require_once __DIR__ . '/../../helpers/blog.php';

$posts = blog_load_posts();

// Pagination : 9 articles par page, via ?page=N
$perPage = 9;
$totalPosts = count($posts);
$totalPages = max(1, (int) ceil($totalPosts / $perPage));

$currentPageNum = (int) ($_GET['page'] ?? 1);
if ($currentPageNum < 1) {
    $currentPageNum = 1;
}
if ($currentPageNum > $totalPages) {
    $currentPageNum = $totalPages;
}

$offset = ($currentPageNum - 1) * $perPage;
$cards = array_slice($posts, $offset, $perPage);
?>

<section id="parole-de-pro">

    <h2 class="parole-title">
        Les conseils de l’expert
    </h2>

    <?php if (empty($posts)): ?>
        <p class="featured-intro" style="text-align:center;">Aucun article pour le moment.</p>
    <?php endif; ?>

    <!-- INTRODUCTION RUBRIQUE -->
    <div class="parole-featured">
        <div class="container-inner-blog">

            <div class="featured-text">
                <span class="featured-label" style="color:#0f4c81;">PAROLES DE PRO</span>

                <p class="featured-intro">
                    Retrouvez dans notre rubrique Paroles de pro des articles, conseils pratiques
                    et astuces d’experts autour de l’assainissement, du débouchage, du curage et
                    de l’entretien des canalisations.
                </p>

                <p class="seo-text">
                    Notre objectif : vous aider à mieux comprendre les signes d’alerte, adopter les
                    bons réflexes et éviter les mauvaises surprises comme les canalisations bouchées,
                    les remontées d’eaux usées, les mauvaises odeurs ou les interventions d’urgence coûteuses.
                </p>

                <p class="seo-text">
                    À travers nos contenus, nous partageons notre expérience terrain pour vous
                    accompagner au quotidien, que vous soyez particulier, professionnel ou syndic
                    de copropriété.
                </p>
            </div>

            <div class="featured-visual">
                <img
                    src="<?= BASE_URL ?>/assets/img/mascotte-blog.png"
                    alt="Mascotte SAHP - Paroles de Pro assainissement">
            </div>

        </div>
    </div>

    <!-- ARTICLES BLOG (DYNAMIQUE) -->
    <div class="parole-articles">

        <?php foreach ($cards as $post): ?>
            <?php $coverImageUrl = blog_resolve_cover_image_url_for_card($post['cover_image'] ?? ''); ?>
            <article class="article-card">
                <div class="article-image">
                    <img
                        src="<?= blog_escape($coverImageUrl) ?>"
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

    <!-- PAGINATION -->
    <?php if ($totalPages > 1): ?>
        <nav class="parole-pagination" aria-label="Pagination des articles">
            <?php if ($currentPageNum > 1): ?>
                <a class="pagination-link pagination-prev" href="<?= BASE_URL ?>/paroles-de-pro?page=<?= $currentPageNum - 1 ?>">← Précédent</a>
            <?php endif; ?>

            <?php for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++): ?>
                <?php if ($pageNumber === $currentPageNum): ?>
                    <span class="pagination-link is-active" aria-current="page"><?= $pageNumber ?></span>
                <?php else: ?>
                    <a class="pagination-link" href="<?= BASE_URL ?>/paroles-de-pro?page=<?= $pageNumber ?>"><?= $pageNumber ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($currentPageNum < $totalPages): ?>
                <a class="pagination-link pagination-next" href="<?= BASE_URL ?>/paroles-de-pro?page=<?= $currentPageNum + 1 ?>">Suivant →</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

</section>