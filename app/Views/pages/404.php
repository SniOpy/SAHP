<!-- 404.php -->
<main class="page-404">
  <div class="container-404">

    <h1>ERREUR 404</h1>

    <p class="subtitle">
      Oups ! Cette page est bouchée.<br />
      Il semble que vous ayez atteint une impasse.
    </p>

    <div class="illustration-container">
      <img
        src="<?= BASE_URL ?>/assets/img/404.png"
        alt="Mascotte SAHP devant un tuyau cassé avec des points d'interrogation"
      />
    </div>

    <a href="<?= BASE_URL ?>/" class="btn-home">Retour à l'accueil</a>

    <div class="divider"></div>

    <section class="bottom-grid">

      <!-- CONTACT -->
      <div class="contact-card">
        <h2>Contactez SAHP</h2>

        <div class="contact-line">
          <span class="icon">☎</span>
          <strong>01 76 24 28 84</strong>
        </div>

        <div class="contact-line">
          <span class="icon">✉</span>
          <a href="mailto:contact@sahp-idf.fr">contact@sahp-idf.fr</a>
        </div>

        <div class="contact-line">
          <span class="icon">📍</span>
          <span>4 Rue Aminata Traore, 94460 Valenton</span>
        </div>
      </div>

      <!-- ZONE -->
      <div class="zone-card">
        <h2>Zones d’intervention</h2>

        <!-- Remplace par ta carte (image) OU une iframe google maps -->
        <div class="zone-map">
          <img
            src="<?= BASE_URL ?>/assets/img/intervention.png"
            alt="Carte zones d’intervention SAHP"
          />
        </div>
      </div>

    </section>

  </div>
</main>
