<?php

declare(strict_types=1);

/**
 * Prévisualisation admin d'un article (publié ou brouillon).
 *
 * Reproduit exactement le rendu public : on charge l'article PAR ID
 * (sans filtre is_published), on le passe au template public via le layout,
 * et on active un bandeau de prévisualisation.
 *
 * Accès strictement réservé à un admin connecté (requireAdminLogin).
 */

require_once __DIR__ . '/../../../app/config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once APP_PATH . '/helpers/blog.php';

requireAdminLogin();

$articleId = (int) ($_GET['id'] ?? 0);

$post = $articleId >= 1 ? blog_find_post_by_id($articleId) : null;

if ($post === null) {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="noindex, nofollow">
        <title>Article introuvable | SAHP Admin</title>
    </head>
    <body style="font-family: Roboto, sans-serif; padding: 40px;">
        <h1>Article introuvable</h1>
        <p>L'article demandé n'existe pas ou a été supprimé.</p>
        <p><a href="<?= BASE_URL ?>/admin/articles/index.php">← Retour à la liste des articles</a></p>
    </body>
    </html>
    <?php
    exit;
}

// Variables attendues par le layout public (app/Views/layouts/main.php)
// et par le template article (app/Views/blog/show.php).
$blogArticlePost = $post;
$view = VIEWS_PATH . '/blog/show.php';
$title = blog_resolve_page_title($post);
$meta_description = blog_resolve_meta_description($post);
$canonicalPath = null; // aucune URL canonique pour une prévisualisation

// Active le bandeau de prévisualisation dans le template (opt-in).
$isAdminPreview = true;
$adminPreviewArticleId = $articleId;

require VIEWS_PATH . '/layouts/main.php';
