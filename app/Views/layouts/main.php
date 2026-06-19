<?php
// Déterminer la page actuelle pour charger seulement les CSS nécessaires
require_once __DIR__ . '/../../helpers/performance.php';

if (!isset($view)) {
  $view = VIEWS_PATH . '/pages/404.php';
}

$currentPage = '';
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

$cssFiles = get_css_files_for_page($currentPage);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-MGXQL5DS');
  </script>
  <!-- End Google Tag Manager -->
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars((string) ($title ?? 'SAHP Assainissement'), ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php
  require_once APP_PATH . '/helpers/seo.php';
  $layoutMetaDescription = sahp_truncate_meta_description(
    $meta_description
      ?? "SAHP, entreprise d'assainissement en Île-de-France : débouchage, curage, vidange, pompage et interventions d'urgence 24h/7j. Devis rapide."
  );
  ?>
  <meta name="description" content="<?= htmlspecialchars($layoutMetaDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="cookie-consent-version" content="1">

  <?php
  // SEO : canonical + Open Graph + Twitter Card
  $seoCanonicalPath = $canonicalPath ?? (($viewBasename === '404') ? null : ($request ?? null));
  sahp_render_seo_tags([
    'title' => (string) ($title ?? 'SAHP Assainissement'),
    'description' => $layoutMetaDescription,
    'canonical' => sahp_seo_canonical_url($seoCanonicalPath),
    'image' => $ogImage ?? sahp_seo_default_image(),
    'type' => $ogType ?? 'website',
  ]);
  if ($currentPage === 'accueil') {
    sahp_render_local_business_schema();
  }
  ?>




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
  <!-- Clarity Analytics -->
  <script type="text/javascript">
    (function(c, l, a, r, i, t, y) {
      c[a] = c[a] || function() {
        (c[a].q = c[a].q || []).push(arguments)
      };
      t = l.createElement(r);
      t.async = 1;
      t.src = "https://www.clarity.ms/tag/" + i;
      y = l.getElementsByTagName(r)[0];
      y.parentNode.insertBefore(t, y);
    })(window, document, "clarity", "script", "wrlma1ea2i");
  </script>

  <!-- JavaScript chargé en defer pour ne pas bloquer le rendu -->
  <script src="<?= BASE_URL ?>/assets/js/script.js?v=<?= SAHP_ASSET_VERSION ?>" defer></script>
  <script src="<?= BASE_URL ?>/assets/js/cookies.js?v=<?= SAHP_ASSET_VERSION ?>" defer></script>
</head>

<body id="top">
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MGXQL5DS"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

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