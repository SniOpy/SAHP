<header class="navbar">

  <!-- LOGO -->
  <div class="nav-left">
    <a href="<?= BASE_URL ?>/" class="logo">
      <img
        src="<?= BASE_URL ?>/assets/img/sahp.png"
        alt="SAHP – Assainissement et Plomberie"
        width="250"
        height="auto"
        fetchpriority="high"
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
      
        <a href="https://wa.me/33658017102?text=Bonjour,%20nous%20venons%20de%20votre%20site%20internet%20SAHP%20et%20nous%20avons%20une%20urgence%20assainissement.%20Pouvez-vous%20nous%20recontacter%20s'il vous plait%20?" class="footer-whatsapp" target="_blank" 
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
