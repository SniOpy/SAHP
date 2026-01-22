<header class="navbar">

  <!-- LOGO -->
  <div class="nav-left">
    <a href="<?= BASE_URL ?>/" class="logo">
      <img
        src="<?= BASE_URL ?>/assets/img/sahp.png"
        alt="SAHP – Assainissement et Plomberie"
      />
    </a>
  </div>

  <!-- BURGER -->
  <button class="burger" aria-label="Ouvrir le menu" aria-expanded="false" type="button">
    <span></span>
    <span></span>
    <span></span>
  </button>

  <!-- OVERLAY -->
  <div class="menu-overlay" aria-hidden="true"></div>

  <!-- MENU -->
  <div class="nav-menu" aria-hidden="true">
    <nav>

      <a href="<?= BASE_URL ?>/">Accueil</a>
      <a href="<?= BASE_URL ?>/a-propos">Qui sommes-nous</a>

      <!-- DROPDOWN -->
      <div class="nav-dropdown" style="margin-bottom : 1px;">

        <!-- TOGGLE-->
        <button
          type="button"
          class="dropdown-toggle"
          aria-expanded="false"
          aria-controls="dropdown-interventions"
        >
          Nos interventions
          <span class="chevron" aria-hidden="true">▾</span>
        </button>

        <!-- MENU DROPDOWN -->
        <div
          class="dropdown-menu"
          id="dropdown-interventions"
          role="menu"
        >
          <a href="<?= BASE_URL ?>/debouchage" role="menuitem">Débouchage</a>
          <a href="<?= BASE_URL ?>/curage" role="menuitem">Curage</a>
          <a href="<?= BASE_URL ?>/pompage" role="menuitem">Pompage</a>
          <a href="<?= BASE_URL ?>/inspection" role="menuitem">Inspection vidéo</a>
        </div>

      </div>

      <a href="<?= BASE_URL ?>/paroles-de-pro">Parole de Pros</a>
      <a href="<?= BASE_URL ?>/contact">Contact</a>

    </nav>

    <!-- ACTIONS -->
    <div class="actions">
      <a
        href="https://wa.me/33612345678"
        class="btn-rounded btn-urgent"
      >
        <img
          src="<?= BASE_URL ?>/assets/img/brand/whatsapp.png"
          alt=""
          aria-hidden="true"
        />
        Urgence 24/7
      </a>
    </div>
  </div>

</header>
