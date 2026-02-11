<?php
// Déterminer la page actuelle pour charger seulement les CSS nécessaires
require_once __DIR__ . '/../../helpers/performance.php';

$currentPage = '';
if (isset($view)) {
    $viewPath = str_replace(VIEWS_PATH . '/', '', $view);
    $viewBasename = basename($viewPath, '.php');
    $viewDir = dirname($viewPath);
    
    // Mapper les vues aux noms de pages
    if ($viewDir === 'blog' && $viewBasename === 'show') {
        $currentPage = 'blog_show';
    } elseif ($viewDir === 'pages') {
        $pageMap = [
            'accueil' => 'accueil',
            'contact' => 'contact',
            'devis' => 'devis',
            'about' => 'about',
            'curage' => 'curage',
            'pompage' => 'pompage',
            'inspection' => 'inspection',
            'debouchage' => 'debouchage',
            'urgence' => 'urgence',
            'maintenance-pro' => 'maintenance-pro',
            'paroles-de-pro' => 'paroles-de-pro',
            'tarifs' => 'tarifs',
            'mentions' => 'mentions',
            'cgps' => 'cgps',
            'pc' => 'pc',
            'plansite' => 'plansite',
            '404' => '404',
        ];
        $currentPage = $pageMap[$viewBasename] ?? '';
    }
}

$cssFiles = get_css_files_for_page($currentPage);
?>
<!DOCTYPE html>
<html lang="fr">
  <head> 
    <meta charset="UTF-8" />
    <title><?= $title ?? 'SAHP Assainissement' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="SAHP, entreprise d'assainissement en Île-de-France : débouchage, curage, vidange, pompage et interventions d'urgence 24h/7j. Devis rapide.">

    <!-- Preconnect pour améliorer les performances -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/assets/img/favicon.svg">
    
    <!-- CSS Critique inline pour le above-the-fold -->
    <?= get_critical_css() ?>
    
    <!-- Fonts optimisées avec chargement asynchrone -->
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap"
      rel="stylesheet"
      media="print"
      onload="this.media='all'"
    >
    <noscript>
      <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet"
      >
    </noscript>
    
    <!-- CSS chargés conditionnellement selon la page -->
    <?php foreach ($cssFiles as $cssFile): ?>
      <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/<?= $cssFile ?>?v=20260209-1">
    <?php endforeach; ?>
    
    <!-- JavaScript chargé en defer pour ne pas bloquer le rendu -->
    <script src="<?= BASE_URL ?>/assets/js/script.js?v=20260209-1" defer></script>
  </head>

  <body>

  <header class="navbar card-glass">
    <?php require VIEWS_PATH . '/layouts/header.php'; ?>
  </header>

  <main>
    <?php require $view; ?>   
  </main>

  <footer>
    <?php require VIEWS_PATH . '/layouts/footer.php'; ?>
  </footer>

  </body>
</html>
