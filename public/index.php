<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/config/config.php';

// URL demandée (on nettoie /sahp et /public)
$request = trim(
  str_replace(['/sahp', '/public'], '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)),
  '/'
);

require_once __DIR__ . '/../app/helpers/page_cache.php';
sahp_try_serve_cache(3600); // 1h


// ✅ ROUTES statiques
$routes = [
  ''          => 'accueil.php',
  'a-propos'  => 'about.php',
  'mentions-legales'  => 'mentions.php',
  'conditions-generales-prestations-services'  => 'cgps.php',
  'politique-confidentialite'  => 'pc.php',
  'plan-site'  => 'plansite.php',
  'curage'  => 'curage.php',
  'pompage'  => 'pompage.php',
  'inspection'  => 'inspection.php',
  'debouchage'  => 'debouchage.php',
  'maintenance-pro'  => 'maintenance-pro.php',
  'urgence'  => 'urgence.php',
  'paroles-de-pro'  => 'paroles-de-pro.php', // ✅ listing
  'contact'  => 'contact.php',
  'devis'  => 'devis.php',
];

/* =====================================================
   ✅ ROUTING DYNAMIQUE : /paroles-de-pro/slug
===================================================== */
$slug = null;

// Exemple : "paroles-de-pro/curage-canalisation-quand-le-faire"
if (preg_match('#^paroles-de-pro/([a-z0-9-]+)$#i', $request, $matches)) {
  $slug = $matches[1];

  // On injecte le slug dans $_GET pour le récupérer facilement dans show.php
  $_GET['slug'] = $slug;

  // ✅ vue article dynamique
  $view  = VIEWS_PATH . '/blog/show.php';

  // Le title SEO sera généré dans show.php (avec le JSON)
  $title = "Paroles de Pros | SAHP Assainissement";
}
/* =====================================================
   ROUTES CLASSIQUES
===================================================== */
elseif (array_key_exists($request, $routes)) {
  $view  = VIEWS_PATH . '/pages/' . $routes[$request];
  $title = $request === ""
    ? "Accueil | SAHP Assainissement"
    : ucfirst(str_replace('-', ' ', $request)) . ' | SAHP Assainissement';
}
/* =====================================================
   404
===================================================== */
else {
  http_response_code(404);
  $view  = VIEWS_PATH . '/pages/404.php';
  $title = 'Page introuvable | SAHP';
}

// ✅ LAYOUT UNIQUE
require VIEWS_PATH . '/layouts/main.php';
