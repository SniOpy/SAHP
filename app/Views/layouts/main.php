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
      'gestion-cookies' => 'gestion-cookies',
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
  <meta name="cookie-consent-version" content="1">




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
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Roboto:wght@400;500&display=swap"
    rel="stylesheet"
    media="print"
    onload="this.media='all'">
  <noscript>
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Roboto:wght@400;500&display=swap"
      rel="stylesheet">
  </noscript>

  <!-- CSS chargés conditionnellement selon la page -->
  <?php foreach ($cssFiles as $cssFile): ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/<?= $cssFile ?>?v=<?= SAHP_ASSET_VERSION ?>">
  <?php endforeach; ?>

  <!-- Consent Mode (Google compatible) : blocage par défaut avant tout script non essentiel -->
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('consent', 'default', {
      ad_storage: 'denied',
      analytics_storage: 'denied',
      ad_user_data: 'denied',
      ad_personalization: 'denied',
      functionality_storage: 'granted',
      security_storage: 'granted'
    });
    gtag('set', 'ads_data_redaction', true);
  </script>

  <!-- JavaScript chargé en defer pour ne pas bloquer le rendu -->
  <script src="<?= BASE_URL ?>/assets/js/script.js?v=<?= SAHP_ASSET_VERSION ?>" defer></script>
  <script src="<?= BASE_URL ?>/assets/js/cookies.js?v=<?= SAHP_ASSET_VERSION ?>" defer></script>
</head>

<body id="top">

  <header class="navbar card-glass">
    <?php require VIEWS_PATH . '/layouts/header.php'; ?>
  </header>

  <main>
    <?php require $view; ?>
  </main>

  <footer>
    <?php require VIEWS_PATH . '/layouts/footer.php'; ?>
  </footer>

  <?php require VIEWS_PATH . '/components/cookie-banner.php'; ?>

  <!-- Bouton retour en haut -->
  <a href="#top" id="back-to-top" class="back-to-top" aria-label="Remonter en haut de la page" title="Remonter en haut">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M12 19V5M5 12l7-7 7 7" />
    </svg>
  </a>

</body>

</html>