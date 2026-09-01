  <!-- LOGO -->
  <div class="nav-left">
    <a href="<?= BASE_URL ?>/" class="logo">
      <img
        src="<?= BASE_URL ?>/assets/img/sahp.png"
        alt="SAHP – Assainissement et Plomberie"
        width="250"
        height="auto"
        fetchpriority="high" />
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
      <a href="<?= BASE_URL ?>/a-propos">qui sommes-nous</a>
      <!-- DROPDOWN SERVICES -->
      <div class="nav-dropdown">

        <button
          type="button"
          class="dropdown-toggle"
          aria-expanded="false"
          aria-controls="dropdown-services">
          Nos interventions
          <span class="chevron" aria-hidden="true">▾</span>
        </button>

        <div
          class="dropdown-menu"
          id="dropdown-services"
          role="menu">
          <a href="<?= BASE_URL ?>/debouchage" role="menuitem">Débouchage canalisation</a>
          <a href="<?= BASE_URL ?>/curage" role="menuitem">Curage canalisation</a>
          <a href="<?= BASE_URL ?>/curage" role="menuitem">Hydrocurage</a>
          <a href="<?= BASE_URL ?>/inspection" role="menuitem">Inspection vidéo canalisation</a>
          <a href="<?= BASE_URL ?>/pompage" role="menuitem">Pompage / Vidange</a>
          <a href="<?= BASE_URL ?>/urgence" role="menuitem">Urgence assainissement</a>
        </div>

      </div>

      <!-- DROPDOWN ZONES D'INTERVENTION -->
      <!-- <div class="nav-dropdown">

        <button
          type="button"
          class="dropdown-toggle"
          aria-expanded="false"
          aria-controls="dropdown-zones">
          Zones d'intervention
          <span class="chevron" aria-hidden="true">▾</span>
        </button>

        <div
          class="dropdown-menu"
          id="dropdown-zones"
          role="menu">
          <a href="<?= BASE_URL ?>/debouchage-canalisation-val-de-marne-94" role="menuitem">Val-de-Marne 94</a>
          <?php /* Pages zone à activer : assainissement-paris, assainissement-ile-de-france */ ?>
          <a href="<?= BASE_URL ?>/contact" role="menuitem">Paris</a>
          <a href="<?= BASE_URL ?>/contact" role="menuitem">Île-de-France</a>
        </div>

      </div> -->

      <a href="<?= BASE_URL ?>/paroles-de-pro">Paroles de pro</a>
      <a href="<?= BASE_URL ?>/contact">Contact</a>

    </nav>

    <!-- ACTIONS -->
    <div class="actions">

      <a style="text-decoration: none;" href="https://wa.me/33658017102?text=Bonjour,%20nous%20venons%20de%20votre%20site%20internet%20SAHP%20et%20nous%20avons%20une%20urgence%20assainissement.%20Pouvez-vous%20nous%20recontacter%20s'il vous plait%20?" class="footer-whatsapp" target="_blank"
        class="btn-rounded btn-urgent">
        <img
          src="<?= BASE_URL ?>/assets/img/brand/whatsapp.png"
          alt="Icône WhatsApp SAHP urgence 24h/7" />
        WhatsApp 24/7
      </a>
    </div>
  </div>