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
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=20260122-1">

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
