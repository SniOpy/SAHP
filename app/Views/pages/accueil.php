<?php $title = "Accueil"; 


require_once __DIR__ . '/../../helpers/blog.php';

// On affiche ensuite 3 cards (ou plus si tu veux)
$posts = blog_load_posts();
$cards = array_slice($posts, 0, 3);
?>

<section class="hero">
  <div class="hero-content">
    <h1>
      Votre réseau d’assainissement<br />
      sous contrôle, sans mauvaises surprises.
    </h1>

    <p class="subtitle">
      Inspection, curage et dépannage rapide pour particuliers et professionnels.
    </p>

    <ul class="hero-list">
      <li>Curage haute pression des évacuations</li>
      <li>Interventions particuliers et professionnels</li>
      <li>Urgence débouchage 24/7</li>
      <li>Contrôle caméra & diagnostic</li>
    </ul>

    <div class="hero-cta">
      <a class="btn-rounded btn-primary" href="<?= BASE_URL ?>/devis">Obtenir un devis rapide</a>
      <a class="btn-rounded" href="tel:+33176242884">Contacter un agent</a>
    </div>
  </div>

  <div class="hero-visual">
    <img src="<?= BASE_URL ?>/assets/img/intervention.jpg" alt="Mascotte SAHP" />
  </div>
</section>

<section class="about">
  <div class="about-container">
    <div class="about-content">
      <h2>À propos de SAHP Assainissement</h2>

      <p class="about-intro">
        SAHP Assainissement accompagne particuliers et professionnels pour l’entretien,
        le dépannage et le contrôle de leurs réseaux d’assainissement.
      </p>

      <p>
        Grâce à des équipements professionnels et une expertise terrain, nous intervenons
        rapidement pour résoudre durablement les problèmes de canalisations, tout en
        garantissant transparence, efficacité et sécurité.
      </p>

      <ul class="about-points">
        <li>Interventions rapides et maîtrisées</li>
        <li>Matériel haute pression & inspection vidéo</li>
        <li>Spécialiste copropriétés et professionnels</li>
        <li>Disponibilité urgence 24/7</li>
      </ul>

      <div class="about-cta">
        <a class="btn-rounded btn-primary" href="<?= BASE_URL ?>/a-propos">En savoir plus...</a>
      </div>
    </div>

    <div class="about-visual">
      <div class="about-image-wrapper">
        <img src="<?= BASE_URL ?>/assets/img/hero.jpg" alt="Intervention assainissement SAHP" />
      </div>
    </div>
  </div>
</section>

<section class="services">
  <div class="services-container">
    <h2>Nos solutions d’assainissement</h2>
    <p class="services-intro">
      Des interventions ciblées, réalisées avec des équipements professionnels pour garantir la
      durabilité de vos installations.
    </p>

    <div class="services-grid">

    <article class="service-card">
        <div class="service-icon">
          <img src="<?= BASE_URL ?>/assets/img/icons/detartrage.jpg" alt="Débouchage canalisations" />
        </div>
        <h3>Débouchage & Détartrage</h3>
        <p>Intervention rapide pour éliminer les bouchons.</p>
        <a href="<?= BASE_URL ?>/debouchage">En savoir plus</a>
      </article>
      
      <article class="service-card">
        <div class="service-icon">
          <img src="<?= BASE_URL ?>/assets/img/icons/curage.jpg" alt="Curage haute pression" />
        </div>
        <h3>Curage haute pression</h3>
        <p>Nettoyage en profondeur des canalisations pour éliminer les dépôts.</p>
        <a href="<?= BASE_URL ?>/curage">En savoir plus</a>
      </article>

      <article class="service-card">
        <div class="service-icon">
          <img src="<?= BASE_URL ?>/assets/img/icons/video.jpg" alt="Inspection vidéo" />
        </div>
        <h3>Inspection vidéo</h3>
        <p>Diagnostic précis grâce à des caméras professionnelles.</p>
        <a href="<?= BASE_URL ?>/inspection">En savoir plus</a>
      </article>

      <article class="service-card">
        <div class="service-icon">
          <img src="<?= BASE_URL ?>/assets/img/icons/pompage.jpg" alt="Pompage et vidange" style="width: 197px;" />
        </div>
        <h3>Pompage & vidange</h3>
        <p>Vidange de fosses, bacs et réseaux encombrés.</p>
        <a href="<?= BASE_URL ?>/pompage">En savoir plus</a>
      </article>

      

      <article class="service-card">
        <div class="service-icon">
          <img src="<?= BASE_URL ?>/assets/img/icons/maintenance.jpg" alt="Maintenance préventive" />
        </div>
        <h3>Maintenance & Entretien</h3>
        <p>Entretien préventif pour éviter pannes et sinistres.</p>
        <a href="<?= BASE_URL ?>/maintenance-pro">En savoir plus</a>
      </article>

      <article class="service-card">
        <div class="service-icon">
          <img src="<?= BASE_URL ?>/assets/img/icons/urgence.jpg" alt="Urgence assainissement 24/7" />
        </div>
        <h3>Urgence 24/7</h3>
        <p>Service d’intervention immédiate, jour et nuit.</p>
        <a href="<?= BASE_URL ?>/urgence">En savoir plus</a>
      </article>
    </div>
  </div>

  <div class="services-mascotte-float">
    <img
      src="<?= BASE_URL ?>/assets/img/intervention.jpg"
      alt="Mascotte intervention assainissement SAHP"
      loading="lazy"
      width="420"
      height="420"
    />
  </div>
</section>

<div class="reviews-separator">
  <img src="<?= BASE_URL ?>/assets/img/sahp.png" alt="SAHP" class="separator-logo" />
</div>

<section id="last-articles" class="last-articles-section">
  <div class="container">

    <header class="section-header">
      <h2>Derniers articles & conseils d’experts</h2>
      <p>
        Astuces, prévention et expertise en assainissement, curage et débouchage
        pour particuliers et professionnels.
      </p>
    </header>

    <div class="articles-grid">

      <!-- <article class="article-card">
        <div class="article-image">
          <img src="<?= BASE_URL ?>/assets/img/blog/debouchage-canalisation.png" alt="Débouchage de canalisation">
        </div>
        <div class="article-content">
          <span class="article-category">Débouchage</span>
          <h3>Canalisation bouchée : causes fréquentes et solutions durables</h3>
          <p>
            Découvrez pourquoi vos canalisations se bouchent et comment éviter
            les interventions d’urgence coûteuses.
          </p>
          <a href="/blog/canalisation-bouchee-causes-solutions" class="article-link">
            Lire l’article →
          </a>
        </div>
      </article> -->

      <?php foreach ($cards as $post): ?>
      <article class="article-card">
        <div class="article-image">
          <img
            src="<?= BASE_URL ?>/assets/img/blog/<?= blog_escape($post['cover_image'] ?? '') ?>"
            alt="<?= blog_escape($post['title']) ?>"
            loading="lazy"
          >
        </div>

        <div class="article-content">
          <span class="article-category"><?= blog_escape($post['category'] ?? 'Conseils') ?></span>

          <h3><?= blog_escape($post['title']) ?></h3>

          <p><?= blog_escape($post['excerpt'] ?? '') ?></p>

          <a href="<?= BASE_URL ?>/paroles-de-pro/<?= blog_escape($post['slug']) ?>" class="article-link">
            Lire l’article →
          </a>
        </div>
      </article>
    <?php endforeach; ?>


      

    </div>

    <div class="section-cta">
      <a href="<?= BASE_URL ?>/paroles-de-pro" class="btn-primary">
        Voir tous les articles
      </a>
    </div>

  </div>
</section>

<section class="reviews">
  <div class="reviews-container">
    <span class="reviews-label">Avis clients</span>
    <h2>La satisfaction client au cœur de notre métier</h2>

    <div class="reviews-score card-glass-reviews">
      <div class="score-left">
        <strong>Excellent 4.9/5</strong>
        <div class="stars-google">★★★★★</div>
      </div>
      <div class="score-right">
        <img src="<?= BASE_URL ?>/assets/img/brand/google.svg" alt="Google brand" />
      </div>
    </div>

    <div class="reviews-grid">
      <article class="review-card card-glass-reviews">
        <div class="stars">★★★★★</div>
        <p class="review-text">
          Un grand merci à l’équipe, du manager au technicien sur place, ils sont intervenus en urgence dans la foulée( la journée) pour déboucher mon assainissement, le technicien connaissait très bien son sujet aucune hésitation, c’est plié en 15 minutes … bravo à vous et merci encore …
        </p>
        <div class="review-author">
          <img src="<?= BASE_URL ?>/assets/img/icons/avatar-homme.png" alt="Client SAHP" />
          <div class="author-info">
            <strong>Mehand Baleh</strong>
            <span>2 mois</span>
          </div>
        </div>
      </article>

      <article class="review-card card-glass-reviews">
        <div class="stars">★★★★★</div>
        <p class="review-text">
          Excellente entreprise sérieuse et CONSCIENCIEUSE a qui j'ai fait appel à 2 reprises ces derniers mois.
          Intervention rapide et soignée, pas de mauvaise surprise au moment de la facture car le prix vous est communiqué avant intervention.
          Le gérant est disponible et prend son temps pour répondre à vos demandes.
        </p>
        <div class="review-author">
          <img src="<?= BASE_URL ?>/assets/img/icons/avatar-femme.png" alt="Client SAHP" />
          <div>
            <strong>Irène FILIPE</strong>
            <span>1 an</span>
          </div>
        </div>
      </article>

      <article class="review-card card-glass-reviews">
        <div class="stars">★★★★★</div>
        <p class="review-text">
          Merci beaucoup à Mourad pour son intervention ! Un grand merci également à l’équipe pour avoir pris en charge une urgence : une canalisation d’évier totalement bouchée. Travail impeccable, soigné et réalisé avec le sourire 👍
        </p>
        <div class="review-author">
          <img src="<?= BASE_URL ?>/assets/img/icons/avatar-homme.png" alt="Client SAHP" />
          <div>
            <strong>Enzo VMB</strong>
            <span>4 mois</span>
          </div>
        </div>
      </article>
    </div>

    <a
      target="_blank"
      href="https://www.google.com/search?client=firefox-b-d&sca_esv=6dfd04640b18e1d6&sxsrf=ANbL-n5exdaoJQKhjnhO-qYpfUvVy7eNkw:1769033489011&si=AL3DRZEsmMGCryMMFSHJ3StBhOdZ2-6yYkXd_doETEE1OR-qOQQx6nqeVfb8TxDpasQh8xWjuj-DUdx6LzI_Cfnf1y6AYrwUe9Rv6mMEFLONw4t3brReK6Z4NCNQ_SoE3nCCICgkl80QqpF1HRbMgRC2l55JLqIqCZmHCqWZcHNCZfOt2YqYRmo%3D&q=Débouchage+Canalisation+Paris+IDF+-+SAHP+Avis&sa=X&ved=2ahUKEwimitWl052SAxWvKvsDHUJJKHsQ0bkNegQIThAH&biw=1696&bih=829&dpr=1.1&aic=0"
      class="reviews-cta"
    >Lire tous nos avis sur Google</a>

    <h3 class="partners-title">Ils nous confient leurs réseaux</h3>
    <p class="partners-subtitle">Syndics, agences immobilières et entreprises partenaires</p>

    <div class="partners-wrapper">

  <div class="partners-slider card-glass-reviews-brand desktop-slider">
    <div class="partners-track">

      <!-- LISTE 1 -->
      <img src="<?= BASE_URL ?>/assets/img/brand/BOUYGUES2.png" alt="Bouygues" />
      <img src="<?= BASE_URL ?>/assets/img/brand/CROUS.png" alt="Crous de Paris" />
      <img src="<?= BASE_URL ?>/assets/img/brand/ENGIE.png" alt="Engie" />
      <img src="<?= BASE_URL ?>/assets/img/brand/ISSY.png" alt="Mairie d'Issy-les-Moulineaux" />
      <img src="<?= BASE_URL ?>/assets/img/brand/YERRES.png" alt="Ville de Yerres" />
      <img src="<?= BASE_URL ?>/assets/img/brand/MONGERON.png" alt="Ville de Montgeron" />
      <img src="<?= BASE_URL ?>/assets/img/brand/PANTIN.png" alt="Mairie de Pantin" />
      <img src="<?= BASE_URL ?>/assets/img/brand/OPH.png" alt="OPH" />
      <img src="<?= BASE_URL ?>/assets/img/brand/VSG.png" alt="Mairie de Villeneuve-Saint-Georges" />
      <img src="<?= BASE_URL ?>/assets/img/brand/EMMAUS.png" alt="Emmaüs" />
      <img src="<?= BASE_URL ?>/assets/img/brand/SPIE.png" alt="SPIE" />

      <!-- LISTE 2 (DUPLICATION POUR LOOP) -->
      <img src="<?= BASE_URL ?>/assets/img/brand/BOUYGUES2.png" alt="Bouygues" />
      <img src="<?= BASE_URL ?>/assets/img/brand/CROUS.png" alt="Crous de Paris" />
      <img src="<?= BASE_URL ?>/assets/img/brand/ENGIE.png" alt="Engie" />
      <img src="<?= BASE_URL ?>/assets/img/brand/ISSY.png" alt="Mairie d'Issy-les-Moulineaux" />
      <img src="<?= BASE_URL ?>/assets/img/brand/YERRES.png" alt="Ville de Yerres" />
      <img src="<?= BASE_URL ?>/assets/img/brand/MONGERON.png" alt="Ville de Montgeron" />
      <img src="<?= BASE_URL ?>/assets/img/brand/PANTIN.png" alt="Mairie de Pantin" />
      <img src="<?= BASE_URL ?>/assets/img/brand/OPH.png" alt="OPH" />
      <img src="<?= BASE_URL ?>/assets/img/brand/VSG.png" alt="Mairie de Villeneuve-Saint-Georges" />
      <img src="<?= BASE_URL ?>/assets/img/brand/EMMAUS.png" alt="Emmaüs" />
      <img src="<?= BASE_URL ?>/assets/img/brand/SPIE.png" alt="SPIE" />

    </div>
  </div>

</div>


    <div class="reviews-mascotte">
      <img src="<?= BASE_URL ?>/assets/img/mascotte.png" alt="Mascotte SAHP" />
    </div>

  </div>
</section>
