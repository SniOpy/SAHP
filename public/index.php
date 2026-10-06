<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/config/config.php';
require_once APP_PATH . '/helpers/seo.php';

// URL demandée (on retire uniquement le préfixe /sahp/public, pas les segments qui contiennent « sahp »)
$request = trim(
    (string) preg_replace('#^(?:/sahp)?(?:/public)?(?=/|$)#i', '', (string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)),
    '/'
);

// Contexte SEO partagé avec le layout (canonical, Open Graph, Twitter)
$canonicalPath = null;
$ogImage = sahp_seo_default_image();
$ogType = 'website';

// ✅ ROUTES statiques
$routes = [
    ''          => 'accueil.php',
    'a-propos'  => 'about.php',
    'mentions-legales'  => 'mentions.php',
    'conditions-generales-prestations-services'  => 'cgps.php',
    'politique-confidentialite'  => 'pc.php',
    'gestion-cookies'  => 'gestion-cookies.php',
    'plan-site'  => 'plansite.php',
    'curage'  => 'curage.php',
    'pompage'  => 'pompage.php',
    'inspection'  => 'inspection.php',
    'debouchage'  => 'debouchage.php',
    'debouchage-canalisation-val-de-marne-94'  => 'debouchage-val-de-marne.php',
    'inspection-video-canalisation-creteil'  => 'inspection-video-canalisation-creteil.php',
    'pompage-assainissement-choisy-le-roi'  => 'pompage-assainissement-choisy-le-roi.php',
    'maintenance-pro'  => 'maintenance-pro.php',
    'urgence'  => 'urgence.php',
    'paroles-de-pro'  => 'paroles-de-pro.php', // ✅ listing
    'contact'  => 'contact.php',
    'devis'  => 'devis.php',
    'tarifs'  => 'tarifs.php',
    'documents/brochure'  => 'documents/brochure.php',
    'documents/plaquette' => 'documents/plaquette.php',
    'equipes/sm'          => 'equipes/sm.php',
    'equipes/fm'          => 'equipes/fm.php',
    'equipes/sahp'        => 'equipes/sahp.php',
];

/* =====================================================
   ✅ ROUTING DYNAMIQUE : /paroles-de-pro/slug
===================================================== */
$blogArticlePost = null;
$slug = null;

// Exemple : "paroles-de-pro/curage-canalisation-quand-le-faire"
if (preg_match('#^paroles-de-pro/([a-z0-9-]+)$#i', $request, $matches)) {
    $slug = $matches[1];

    // On injecte le slug dans $_GET pour les vues
    $_GET['slug'] = $slug;

    require_once APP_PATH . '/helpers/blog.php';
    $blogArticlePost = blog_find_post_by_slug($slug);

    if ($blogArticlePost === null) {
        http_response_code(404);
        $view  = VIEWS_PATH . '/pages/404.php';
        $title = 'Page introuvable (404) | SAHP Assainissement IDF';
    } else {
        $view  = VIEWS_PATH . '/blog/show.php';
        $title = blog_resolve_page_title($blogArticlePost);
        $meta_description = blog_resolve_meta_description($blogArticlePost);
        $canonicalPath = 'paroles-de-pro/' . $slug;
        $ogType = 'article';
    }
}
/* =====================================================
   ROUTES CLASSIQUES
===================================================== */ elseif (array_key_exists($request, $routes)) {
    $view  = VIEWS_PATH . '/pages/' . $routes[$request];
    $canonicalPath = $request;

    $seo = sahp_seo_for_path($request);
    if ($seo !== null) {
        $title = $seo['title'];
        $meta_description = $seo['description'];
        $ogImage = $seo['image'] ?? sahp_seo_default_image();
    } else {
        // Repli si une route n'a pas (encore) d'entrée SEO dédiée
        $title = $request === ''
            ? 'Accueil | SAHP Assainissement'
            : ucfirst(str_replace('-', ' ', $request)) . ' | SAHP Assainissement';
    }
}
/* =====================================================
   404
===================================================== */ else {
    http_response_code(404);
    $view  = VIEWS_PATH . '/pages/404.php';
    $title = 'Page introuvable | SAHP';
}

// ✅ LAYOUT UNIQUE
require VIEWS_PATH . '/layouts/main.php';
