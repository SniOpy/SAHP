<?php
require_once __DIR__ . '/../../helpers/blog.php';

$posts = blog_load_posts();
$cards = array_slice($posts, 0, 3);
?>

<section class="hero">
  <div class="hero-content">
    <h1>
      Votre réseau d'assainissement<br />
      sous contrôle, sans mauvaises surprises.
    </h1>

    <p class="subtitle">
      Depuis 2015, on dépanne, on nettoie et on contrôle les canalisations des particuliers,
      des syndics et des entreprises en Île-de-France — souvent le jour même.
    </p>

    <ul class="hero-list">
      <li>Curage haute pression des évacuations</li>
      <li>Interventions particuliers et professionnels</li>
      <li>Urgence débouchage 24/7</li>
      <li>Contrôle caméra & diagnostic</li>
    </ul>
  </div>

  <div class="hero-visual">
    <img
      src="<?= BASE_URL ?>/assets/img/intervention.jpg"
      alt="Technicien SAHP en intervention d'assainissement en Île-de-France"
      loading="lazy"
      decoding="async"
      width="350"
      height="auto" />
  </div>
</section>

<section class="about" id="about">
  <div class="about-container">
    <div class="about-content">
      <h2>À propos de SAHP</h2>

      <p class="about-intro">
        SAHP, c'est une entreprise familiale basée à Valenton. On ne fait qu'une chose,
        mais on la fait bien : l'assainissement des eaux usées et pluviales.
      </p>

      <p>
        Quand un évier ralentit un dimanche soir ou qu'une colonne d'immeuble refoule
        chez plusieurs voisins, on envoie une équipe équipée — camion hydrocureur, caméra,
        pompe — pas juste un déboucheur manuel et un espoir. C'est ce qui fait la différence
        entre un dépannage qui tient deux semaines et une solution qui dure.
      </p>

      <p>
        On travaille autant pour des maisons individuelles que pour des copropriétés,
        des restaurants, des collectivités (Pantin, Issy, Yerres…) et des grands comptes.
        Le prix vous est annoncé avant l'intervention. Pas de surprise sur la facture :
        nos clients le disent souvent dans leurs avis Google.
      </p>

      <ul class="about-points">
        <li>Équipes sur le terrain, pas un call center anonyme</li>
        <li>Matériel haute pression & inspection vidéo embarquée</li>
        <li>Habitués des copropriétés et des sites sensibles</li>
        <li>Disponibles 24h/7j, y compris jours fériés</li>
      </ul>

      <div class="about-cta">
        <a class="btn-rounded btn-primary" href="<?= BASE_URL ?>/a-propos">Découvrir notre histoire</a>
      </div>
    </div>

    <div class="about-visual">
      <div class="about-image-wrapper">
        <img
          src="<?= BASE_URL ?>/assets/img/team.jpg"
          alt="équipe SAHP en intervention de curage en Île-de-France"
          loading="lazy"
          decoding="async"
          width="600"
          height="auto" />
      </div>
    </div>
  </div>
</section>

<section class="services" id="services">
  <div class="services-container">
    <h2>Nos solutions d'assainissement</h2>
    <p class="services-intro">
      Chaque situation appelle une méthode différente. On commence par comprendre
      ce qui se passe dans vos tuyaux — ensuite seulement, on choisit l'outil adapté.
    </p>

    <div class="services-grid">

      <article class="service-card">
        <div class="service-icon">
          <img
            src="<?= BASE_URL ?>/assets/img/icons/detartrage.jpg"
            alt="Icône débouchage et détartrage de canalisation"
            loading="lazy"
            decoding="async"
            width="235"
            height="auto" />
        </div>
        <h3>Débouchage & Détartrage</h3>
        <p>
          WC, évier, douche ou colonne d'immeuble : on élimine le bouchon mécaniquement
          ou par hydrocurage, sans produits chimiques qui abîment vos joints.
        </p>
        <a href="<?= BASE_URL ?>/debouchage">Débouchage de canalisation en urgence</a>
      </article>

      <article class="service-card">
        <div class="service-icon">
          <img
            src="<?= BASE_URL ?>/assets/img/icons/curage.jpg"
            alt="Icône curage haute pression de canalisation"
            loading="lazy"
            decoding="async"
            width="235"
            height="auto" />
        </div>
        <h3>Curage haute pression</h3>
        <p>
          Quand les bouchons reviennent souvent, le problème est en général plus profond.
          Le curage remet les parois à nu et restitue le diamètre d'origine du conduit.
        </p>
        <a href="<?= BASE_URL ?>/curage">Curage haute pression des canalisations</a>
      </article>

      <article class="service-card">
        <div class="service-icon">
          <img
            src="<?= BASE_URL ?>/assets/img/icons/video.jpg"
            alt="Icône inspection vidéo de canalisation par caméra"
            loading="lazy"
            decoding="async"
            width="235"
            height="auto" />
        </div>
        <h3>Inspection vidéo</h3>
        <p>
          Avant de casser du carrelage ou de remplacer une canalisation entière,
          la caméra permet de voir précisément où se situe le défaut.
        </p>
        <a href="<?= BASE_URL ?>/inspection">Inspection caméra de réseau</a>
      </article>

      <article class="service-card">
        <div class="service-icon">
          <img
            src="<?= BASE_URL ?>/assets/img/icons/pompage.jpg"
            alt="Icône pompage et vidange de fosse septique"
            style="width: 100px;"
            loading="lazy"
            decoding="async"
            width="197"
            height="auto" />
        </div>
        <h3>Pompage & vidange</h3>
        <p>
          Fosse septique pleine, bac à graisse saturé, eaux usées à évacuer :
          nos camions pompe interviennent rapidement avec reçu de vidange si besoin.
        </p>
        <a href="<?= BASE_URL ?>/pompage">Pompage et vidange en Île-de-France</a>
      </article>

      <article class="service-card">
        <div class="service-icon">
          <img
            src="<?= BASE_URL ?>/assets/img/icons/maintenance.jpg"
            alt="Icône maintenance préventive des réseaux d'assainissement"
            loading="lazy"
            decoding="async"
            width="235"
            height="auto" />
        </div>
        <h3>Maintenance & Entretien</h3>
        <p>
          Pour les pros et les copropriétés, un contrat d'entretien planifié
          coûte toujours moins cher qu'une urgence un dimanche matin.
        </p>
        <a href="<?= BASE_URL ?>/maintenance-pro">Contrats maintenance professionnels</a>
      </article>

      <article class="service-card">
        <div class="service-icon">
          <img
            src="<?= BASE_URL ?>/assets/img/icons/urgence.jpg"
            alt="Icône urgence assainissement disponible 24h sur 7"
            loading="lazy"
            decoding="async"
            width="235"
            height="auto" />
        </div>
        <h3>Urgence 24/7</h3>
        <p>
          Refoulement, débordement, fosse pleine : appelez-nous directement.
          On priorise les situations où chaque heure d'attente aggrave les dégâts.
        </p>
        <a href="<?= BASE_URL ?>/urgence">Service d'urgence assainissement 24h/7j</a>
      </article>
    </div>
  </div>

  <div class="services-mascotte">
    <img
      src="<?= BASE_URL ?>/assets/img/mascotte1.png"
      alt="Mascotte plombier SAHP présentant les services d'assainissement"
      loading="lazy"
      decoding="async"
      width="200"
      height="auto" />
  </div>
</section>

<div class="reviews-separator-mascotte">
  <img
    src="<?= BASE_URL ?>/assets/img/sahp.png"
    alt="Logo SAHP Assainissement"
    class="separator-logo"
    loading="lazy"
    decoding="async"
    width="250"
    height="auto" />
</div>

<section id="last-articles" class="last-articles-section">
  <div class="container">

    <header class="section-header">
      <h2>Derniers articles & conseils d'experts</h2>
      <p>
        Astuces, prévention et expertise en assainissement, curage et débouchage
        pour particuliers et professionnels.
      </p>
    </header>

    <?php if (empty($cards)): ?>
      <p class="section-header" style="text-align:center;">Aucun article pour le moment.</p>
    <?php endif; ?>

    <div class="articles-grid">
      <?php foreach ($cards as $post): ?>
        <?php $coverImageUrl = blog_resolve_cover_image_url_for_card($post['cover_image'] ?? ''); ?>
        <article class="article-card">
          <div class="article-image">
            <img
              src="<?= blog_escape($coverImageUrl) ?>"
              alt="<?= blog_escape($post['title']) ?>"
              loading="lazy">
          </div>

          <div class="article-content">
            <span class="article-category"><?= blog_escape($post['category'] ?? 'Conseils') ?></span>

            <h3><?= blog_escape($post['title']) ?></h3>

            <p><?= blog_escape($post['excerpt'] ?? '') ?></p>

            <a href="<?= BASE_URL ?>/paroles-de-pro/<?= blog_escape($post['slug']) ?>" class="article-link">
              Lire l'article →
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

<div class="reviews-separator">
  <img
    src="<?= BASE_URL ?>/assets/img/sahp.png"
    alt="Logo SAHP Assainissement"
    class="separator-logo"
    loading="lazy"
    decoding="async"
    width="250"
    height="auto" />
</div>

<section class="reviews" id="avis">
  <div class="reviews-container">
    <span class="reviews-label">Avis clients</span>
    <h2>La satisfaction client au cœur de notre métier</h2>

    <div class="reviews-score card-glass-reviews">
      <div class="score-left">
        <strong>Excellent 4.9/5</strong>
        <div class="stars-google">★★★★★</div>
      </div>
      <div class="score-right">
        <img
          src="<?= BASE_URL ?>/assets/img/brand/google.svg"
          alt="Logo Google Avis clients"
          loading="lazy"
          decoding="async"
          width="80"
          height="80" />
      </div>
    </div>

    <div class="reviews-grid">
      <article class="review-card card-glass-reviews">
        <div class="stars">★★★★★</div>
        <p class="review-text">
          Un grand merci à l'équipe, du manager au technicien sur place, ils sont intervenus en urgence dans la foulée( la journée) pour déboucher mon assainissement, le technicien connaissait très bien son sujet aucune hésitation, c'est plié en 15 minutes … bravo à vous et merci encore …
        </p>
        <div class="review-author">
          <img
            src="<?= BASE_URL ?>/assets/img/icons/avatar-homme.png"
            alt="Photo de profil de Mehand Baleh, avis Google SAHP"
            loading="lazy"
            decoding="async"
            width="50"
            height="50" />
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
          <img
            src="<?= BASE_URL ?>/assets/img/icons/avatar-femme.png"
            alt="Photo de profil d'Irène Filipe, avis Google SAHP"
            loading="lazy"
            decoding="async"
            width="50"
            height="50" />
          <div>
            <strong>Irène FILIPE</strong>
            <span>1 an</span>
          </div>
        </div>
      </article>

      <article class="review-card card-glass-reviews">
        <div class="stars">★★★★★</div>
        <p class="review-text">
          Merci beaucoup à Mourad pour son intervention ! Un grand merci également à l'équipe pour avoir pris en charge une urgence : une canalisation d'évier totalement bouchée. Travail impeccable, soigné et réalisé avec le sourire 👍
        </p>
        <div class="review-author">
          <img
            src="<?= BASE_URL ?>/assets/img/icons/avatar-homme.png"
            alt="Photo de profil d'Enzo VMB, avis Google SAHP"
            loading="lazy"
            decoding="async"
            width="50"
            height="50" />
          <div>
            <strong>Enzo VMB</strong>
            <span>4 mois</span>
          </div>
        </div>
      </article>
    </div>

    <a
      target="_blank"
      rel="noopener noreferrer"
      href="https://www.google.com/search?client=opera&hs=SIX&sca_esv=ec2bff8bd1e2ef21&sxsrf=ANbL-n4Ru34tT3sCM6AG1XawDpEPMcb27A:1781610364127&q=sahp+debouchage&si=AL3DRZEsmMGCryMMFSHJ3StBhOdZ2-6yYkXd_doETEE1OR-qOQQx6nqeVfb8TxDpasQh8xUkdSi6YmHdBJnQqQ1Aaqq3C8ef3AWSPK2SiAD2ExsOXTZ2ftM%3D&uds=ALYpb_n_EM_B_ErB9c5NX69H4GoWDFQEchDJ5N2H9pMMR0rMUrmHeJYEwz2hFhkiLuUT1yEhUkQpyjmZtkavCPTZRSNXos066TupDA5ao_vZMJhQmPiXS6g&sa=X&ved=2ahUKEwjk4Ybn14uVAxXLU6QEHfmaEz4Q3PALegQINBAF&biw=1676&bih=823&dpr=1.1"
      class="reviews-cta">Lire tous nos avis sur Google</a>

    <h3 class="partners-title">Ils nous confient leurs réseaux</h3>
    <p class="partners-subtitle">Syndics, agences immobilières et entreprises partenaires</p>

    <div class="partners-wrapper">

      <div class="partners-slider card-glass-reviews-brand desktop-slider">
        <div class="partners-track">

          <img src="<?= BASE_URL ?>/assets/img/brand/BOUYGUES3.png" alt="Logo client Bouygues" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/CROUS2.png" alt="Logo client Crous de Paris" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/ENGIE3.png" alt="Logo client Engie" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/ISSY3.png" alt="Logo Mairie d'Issy-les-Moulineaux" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/YERRES3.png" alt="Logo Ville de Yerres" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/MONGERON3.png" alt="Logo Ville de Montgeron" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/PANTIN3.png" alt="Logo Mairie de Pantin" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/OPH3.png" alt="Logo OPH partenaire SAHP" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/VSG3.png" alt="Logo Mairie de Villeneuve-Saint-Georges" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/SPIE3.png" alt="Logo client SPIE" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/EMMAUS3.png" alt="Logo client Emmaüs" loading="lazy" decoding="async" width="180" height="auto" />

          <img src="<?= BASE_URL ?>/assets/img/brand/BOUYGUES3.png" alt="Logo client Bouygues" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/CROUS2.png" alt="Logo client Crous de Paris" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/ENGIE3.png" alt="Logo client Engie" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/ISSY3.png" alt="Logo Mairie d'Issy-les-Moulineaux" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/YERRES3.png" alt="Logo Ville de Yerres" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/MONGERON3.png" alt="Logo Ville de Montgeron" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/PANTIN3.png" alt="Logo Mairie de Pantin" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/OPH3.png" alt="Logo OPH partenaire SAHP" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/VSG3.png" alt="Logo Mairie de Villeneuve-Saint-Georges" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/SPIE3.png" alt="Logo client SPIE" loading="lazy" decoding="async" width="180" height="auto" />
          <img src="<?= BASE_URL ?>/assets/img/brand/EMMAUS3.png" alt="Logo client Emmaüs" loading="lazy" decoding="async" width="180" height="auto" />

        </div>
      </div>

    </div>

  </div>

  <div class="home-faq-wrap">
    <?php
    require_once APP_PATH . '/helpers/faq.php';
    sahp_render_faq([
      [
        'q' => 'Quels types d\'interventions d\'assainissement proposez-vous en Île-de-France ?',
        'a' => '<p>On couvre l\'ensemble de la chaîne : <a href="' . BASE_URL . '/debouchage">débouchage</a> de WC, éviers et colonnes, <a href="' . BASE_URL . '/curage">curage haute pression</a>, <a href="' . BASE_URL . '/pompage">pompage et vidange</a> de fosses, <a href="' . BASE_URL . '/inspection">inspection caméra</a> et contrats de <a href="' . BASE_URL . '/maintenance-pro">maintenance</a> pour les professionnels. Chaque intervention commence par un diagnostic : on ne facture pas une prestation lourde si un débouchage ciblé suffit.</p>',
      ],
      [
        'q' => 'Intervenez-vous en urgence le soir et le week-end ?',
        'a' => '<p>Oui, nos équipes sont mobilisables 24h/24 et 7j/7, y compris les jours fériés. Pour un <a href="' . BASE_URL . '/urgence">débordement actif</a>, le plus rapide reste l\'appel téléphonique au 01.76.24.28.84. On vous indique un délai d\'arrivée réaliste et ce que vous pouvez faire en attendant (couper l\'eau, ne plus utiliser les sanitaires concernés).</p>',
      ],
      [
        'q' => 'Comment obtenir un devis gratuit pour un débouchage ou un curage ?',
        'a' => '<p>Remplissez notre <a href="' . BASE_URL . '/devis">formulaire de devis</a> en trois champs — nom, téléphone, type de prestation — ou appelez-nous directement. On vous rappelle généralement dans la journée pour préciser le contexte (type de bâtiment, symptômes, urgence ou non) et vous donner une fourchette tarifaire avant déplacement.</p>',
      ],
      [
        'q' => 'Travaillez-vous avec les particuliers, les syndics et les entreprises ?',
        'a' => '<p>Les trois. En maison individuelle, on intervient pour des bouchons ponctuels ou un entretien préventif. Pour les syndics et gestionnaires, on gère les colonnes d\'immeuble, les réseaux graisseux de restauration et les plannings d\'entretien annuel. Nos références incluent des collectivités et des grands comptes en Île-de-France.</p>',
      ],
      [
        'q' => 'Dans quelles zones géographiques intervenez-vous autour de Paris ?',
        'a' => '<p>Notre base est à Valenton (94). On intervient sur Paris et toute l\'Île-de-France, ainsi que dans l\'Oise (60), l\'Eure (27) et l\'Eure-et-Loir (28). Pour une adresse en limite de zone, contactez-nous : on confirme la faisabilité et le délai avant de planifier le camion.</p>',
      ],
    ], 'Questions fréquentes');
    ?>
  </div>

  <div class="reviews-container">
    <div class="reviews-mascotte">
      <img
        src="<?= BASE_URL ?>/assets/img/mascotte1.png"
        alt="Mascotte SAHP saluant les clients satisfaits"
        loading="lazy"
        decoding="async"
        width="200"
        height="auto" />
    </div>

  </div>
</section>