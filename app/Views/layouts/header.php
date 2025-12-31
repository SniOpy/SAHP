<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <title>SAHP Assainissement – Urgence & Expertise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css" />
  </head>
  <body>

   <header class="navbar ">
      <div class="nav-left">
        <div class="logo">
          <img
            src="<?= BASE_URL ?>/assets/img/sahp.png"
            alt="logo sahp - assainissement et plomberie"
            style="width: 250px"
          />
        </div>
      </div>

      <!-- BURGER -->
      <button class="burger" aria-label="Menu">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <!-- MENU -->
      <div class="nav-menu">
        <nav>
          <a href="<?= BASE_URL ?>/">Accueil</a>
          <a href="<?= BASE_URL ?>/a-propos">Qui sommes-nous</a>

          <!-- DROPDOWN -->
          <div class="nav-dropdown">
            <a href="#" class="dropdown-toggle">
              Nos interventions
              <span class="chevron">▾</span>
            </a>
            <div class="dropdown-menu">
              <a href="<?= BASE_URL ?>/debouchage">Débouchage des canalisations</a>
              <a href="<?= BASE_URL ?>/curage">Curage des canalisations</a>
              <a href="<?= BASE_URL ?>/pompage">Pompage des canalisations</a>
              <a href="<?= BASE_URL ?>/inspection">Inspection vidéo</a>
            </div>
          </div>

          <a href="<?= BASE_URL ?>/paroles-de-pro">Parole de Pros</a>
          <a href="<?= BASE_URL ?>/contact">Contact</a>
        </nav>

        <div class="actions">
          <a href="https://wa.me/33612345678" class="btn-rounded btn-urgent"> <img src="<?= BASE_URL ?>/assets/img/brand/whatsapp.png" alt=""> Urgence 24/7</a>
        </div>
      </div>
    </header>