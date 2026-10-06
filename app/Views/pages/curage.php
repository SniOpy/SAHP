<section class="curage-page">

  <div class="curage-container">
    <div class="container-inner">

    <h1>Curage haute pression des canalisations</h1>

    <p class="curage-intro">
      Le curage haute pression est la solution la plus efficace pour nettoyer
      durablement vos canalisations, éliminer les dépôts et prévenir les
      bouchons récurrents.
      SAHP, <a href="<?= BASE_URL ?>/">entreprise d'assainissement basée à Valenton</a>,
      intervient en Île-de-France avec du matériel professionnel adapté à chaque réseau.
    </p>

    <!-- AVANT / APRÈS (UNE SEULE IMAGE COMPARATIVE) -->
    <h2>Curage haute pression : avant / après intervention</h2>

    <div class="curage-compare">
      <img
        src="<?= BASE_URL ?>/assets/img/curage.jpg"
        alt="Canalisation avant et après un curage haute pression">
      <p class="curage-caption">
        Exemple réel de canalisation avant et après un curage haute pression :
        élimination des dépôts et restitution du diamètre d'origine.
      </p>
    </div>

    <h2>Qu'est-ce que le curage haute pression ?</h2>

    <p>
      Contrairement à un simple débouchage, le curage haute pression consiste à
      nettoyer intégralement l'intérieur des canalisations à l'aide d'un jet
      d'eau projeté à très haute pression.
    </p>

    <p>
      Cette technique permet d'éliminer le tartre, les graisses, les résidus
      organiques et les déchets accumulés sur les parois, afin de restituer le
      diamètre initial des conduits.
    </p>

    <h2>Pourquoi un curage est-il indispensable ?</h2>

    <ul class="curage-list">
      <li>Écoulement lent ou irrégulier des eaux usées</li>
      <li>Mauvaises odeurs persistantes dans les canalisations</li>
      <li>Bruits de gargouillis dans les tuyaux</li>
      <li>Bouchons fréquents malgré des débouchages répétés</li>
    </ul>

    <p class="curage-note">
      Lorsque les interventions de débouchage deviennent régulières,
      le problème est généralement structurel : un curage est alors nécessaire.
    </p>

    <!-- PHOTO INTERVENTION SAHP -->
    <h2>Intervention de curage par SAHP</h2>

    <div class="curage-intervention">
      <img
        src="<?= BASE_URL ?>/assets/img/pompage2.jpg"
        alt="Camion hydrocureur SAHP en intervention de curage">
      <p class="curage-caption">
        Intervention réalisée par nos équipes avec camion hydrocureur
        professionnel en Île-de-France.
      </p>
    </div>

    <h2>Comment se déroule une intervention de curage ?</h2>

    <ol class="curage-steps">
      <li>Analyse de la situation et repérage des zones encrassées</li>
      <li>Introduction d'une buse haute pression adaptée</li>
      <li>Nettoyage complet de la canalisation</li>
      <li>Rinçage et contrôle final de l'écoulement</li>
    </ol>

    <h2>Curage préventif ou curatif</h2>

    <p>
      Le curage peut être réalisé à titre curatif en cas de dysfonctionnement,
      mais il est surtout recommandé en entretien préventif.
    </p>

    <ul class="curage-list">
      <li>Maisons individuelles : tous les 3 à 5 ans</li>
      <li>Immeubles et copropriétés : entretien régulier des colonnes</li>
      <li>Professionnels : bacs à graisse et réseaux soumis à forte charge</li>
    </ul>

    <h2>Pourquoi faire appel à SAHP ?</h2>

    <ul class="curage-list">
      <li>Équipements professionnels haute pression</li>
      <li>Interventions propres et maîtrisées</li>
      <li>Respect des normes environnementales</li>
      <li>Intervention rapide en Île-de-France</li>
    </ul>

    <div class="page-pricing-wrapper">
      <div class="page-pricing-card pricing-category">
        <div class="pricing-ttc-bar">
          <h3 class="pricing-ttc-title">Tarifs</h3>
        </div>
        <div class="pricing-list">
          <div class="pricing-item">
            <span class="service-name">Curage de canalisation jusqu'à 10 mètres linéaires</span>
            <span class="service-price" data-price-ht="350.00">350.00 € HT</span>
          </div>
          <div class="pricing-item">
            <span class="service-name">Curage de canalisation mètre linéaire supplémentaire</span>
            <span class="service-price" data-price-ht="30.00">30.00 € HT</span>
          </div>
          <div class="pricing-item">
            <span class="service-name">Curage de réseau de canalisations</span>
            <span class="service-price">Sur devis</span>
          </div>
          <div class="pricing-item">
            <span class="service-name">Curage de colonne d'immeuble</span>
            <span class="service-price">Sur devis</span>
          </div>
        </div>
        <div class="pricing-btn-wrapper">
          <a href="<?= BASE_URL ?>/devis" class="pricing-btn">Demander un devis gratuit</a>
        </div>
      </div>
    </div>

    <?php
    require_once APP_PATH . '/helpers/local_pages.php';
    sahp_render_service_local_links(
        'curage',
        'Curage canalisation dans le Val-de-Marne',
        'Entretien et curage haute pression de vos canalisations dans le 94 :'
    );
    sahp_render_service_local_links(
        'hydrocurage',
        'Hydrocurage canalisation dans le Val-de-Marne',
        'Nettoyage des réseaux encrassés par jet haute pression dans le Val-de-Marne :'
    );
    ?>

    <?php
    require_once APP_PATH . '/helpers/faq.php';
    sahp_render_faq([
      [
        'q' => 'À quelle fréquence faut-il curer ses canalisations ?',
        'a' => '<p>Pour une maison individuelle, un curage tous les 3 à 5 ans suffit généralement. Dès qu’il y a une forte sollicitation (immeuble, restaurant, site industriel), on passe à un rythme annuel. Le bon repère reste l’usage : si l’eau commence à s’écouler lentement ou que les odeurs reviennent, il est temps d’y penser.</p>',
      ],
      [
        'q' => 'Quelle différence entre un débouchage et un curage ?',
        'a' => '<p>Le <a href="' . BASE_URL . '/debouchage">débouchage</a> règle un bouchon ponctuel, à un endroit précis. Le curage, lui, nettoie toute la longueur de la canalisation et décolle les dépôts sur les parois (graisses, tartre, boues). En clair : le débouchage soulage, le curage prévient les récidives.</p>',
      ],
      [
        'q' => 'Le curage haute pression est-il sans risque pour mes installations ?',
        'a' => '<p>Oui. La pression et le type de buse sont adaptés au diamètre et au matériau du conduit. C’est une méthode mécanique, à l’eau, sans produit chimique. Si le réseau est ancien, on peut le contrôler au préalable par <a href="' . BASE_URL . '/inspection">inspection vidéo</a> pour intervenir en toute sécurité.</p>',
      ],
      [
        'q' => 'Intervenez-vous pour les copropriétés et les professionnels ?',
        'a' => '<p>Oui, c’est même une grande partie de notre activité : colonnes d’immeuble, bacs à graisse, réseaux de restaurants ou de sites logistiques. Pour ces besoins récurrents, un contrat de <a href="' . BASE_URL . '/maintenance-pro">maintenance</a> revient moins cher que des interventions d’urgence répétées.</p>',
      ],
      [
        'q' => 'Comment obtenir un prix pour un curage ?',
        'a' => '<p>Le tarif dépend de la longueur à traiter et de l’accès au réseau. Le plus simple est de nous décrire la situation via une <a href="' . BASE_URL . '/devis">demande de devis</a> gratuite : on vous répond rapidement avec une estimation claire, sans engagement.</p>',
      ],
    ]);
    ?>

    </div>
  </div>

</section>