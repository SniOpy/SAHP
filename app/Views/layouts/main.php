<!DOCTYPE html>
<html lang="fr">
  <head> 
    <meta charset="UTF-8" />
      <title><?= $title ?? 'SAHP Assainissement' ?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta name="description" content="SAHP, entreprise d’assainissement en Île-de-France : débouchage, curage, vidange, pompage et interventions d’urgence 24h/7j. Devis rapide.">

    <!-- Fonts -->
      <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet"
      />
    <!-- Favicon -->
      <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/assets/img/favicon.svg">
    
    <!-- CSS Global -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=20260122-1">
    
    <!-- Homepage CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/homepage/header.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/homepage/hero.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/homepage/about.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/homepage/services.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/homepage/reviews.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/homepage/last-articles.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/homepage/footer.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/homepage/blog.css?v=20260122-1">
    
    <!-- Pages CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/about.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/mentions.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/cgps.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/plansite.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/curage.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/pompage.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/inspection.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/debouchage.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/maintenance-pro.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/urgence.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/404.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/paroles-de-pro.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/contact.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/devis.css?v=20260122-1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/blog-articles.css?v=20260122-1">

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
