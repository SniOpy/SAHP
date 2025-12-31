<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/config/config.php';

// URL
$request = trim(
  str_replace(['/sahp', '/public'], '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)),
  '/'
);

// ROUTES
$routes = [
  ''          => 'accueil.php',
  'a-propos'  => 'about.php',
  'mentions-legales'  => 'mentions.php',
  'conditions-generales-prestations'  => 'cgps.php',
  'politique-confidentialite'  => 'pc.php',
  'plan-site'  => 'plansite.php',
  'curage'  => 'curage.php',
  'pompage'  => 'pompage.php',
  'inspection'  => 'inspection.php',
  'debouchage'  => 'debouchage.php',
  'maintenance-pro'  => 'maintenance-pro.php',
  'urgence'  => 'urgence.php',
  'paroles-de-pro'  => 'paroles-de-pro.php',
  'contact'  => 'contact.php',
  'devis'  => 'devis.php',
];

// ROUTING
if (array_key_exists($request, $routes)) {
  $view  = VIEWS_PATH . '/pages/' . $routes[$request];
  $title = ucfirst(str_replace('-', ' ', $request)) . ' | SAHP';
} else {
  http_response_code(404);
  $view  = VIEWS_PATH . '/pages/404.php';
  $title = 'Page introuvable | SAHP';
}

// LAYOUT UNIQUE
require VIEWS_PATH . '/layouts/main.php';
