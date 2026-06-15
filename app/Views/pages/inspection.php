<section class="inspection-page">

  <div class="inspection-container">
    <div class="container-inner">

      <h1>Inspection vidéo des canalisations et recherche de réseau</h1>

      <p class="inspection-intro">
        L'inspection vidéo permet de voir précisément l'état intérieur de vos
        canalisations et de localiser vos réseaux sans casser.
        Une solution fiable, rapide et non destructive proposée par SAHP.
      </p>

      <!-- IMAGE INTERVENTION -->
      <div class="camion-intervention">
        <p style="text-align: center;">
          <img
            src="<?= BASE_URL ?>/assets/img/inspection-video.jpg"
            alt="Inspection vidéo de canalisation par caméra SAHP" />
        </p>
        <p class="camion-caption" style="text-align: center;">
          Camion d'inspection vidéo de l'équipe SAHP.
        </p>
      </div>

      <h2>Pourquoi réaliser une inspection vidéo ?</h2>

      <p>
        Les canalisations sont invisibles mais essentielles.
        Agir sans diagnostic précis entraîne souvent des travaux inutiles,
        coûteux et destructifs.
      </p>

      <p>
        Grâce à nos caméras endoscopiques étanches, nous explorons l'intérieur
        de vos réseaux d'eaux usées ou pluviales afin d'identifier avec certitude
        l'origine des dysfonctionnements.
      </p>

      <h2>Un diagnostic visuel précis et fiable</h2>

      <ul class="inspection-list">
        <li>Identification de la nature des bouchons (graisses, lingettes, calcaire)</li>
        <li>Détection de fissures, affaissements ou écrasements</li>
        <li>Repérage des défauts de pose (contre-pente, mauvais raccordement)</li>
        <li>Localisation des intrusions de racines</li>
      </ul>

      <h2>Inspection caméra et assurance</h2>

      <p>
        À l'issue de l'intervention, SAHP peut fournir un rapport d'inspection
        vidéo. Ce document constitue une preuve objective souvent exigée par
        les assurances en cas de dégât des eaux ou de litige immobilier.
      </p>

      <h2>Recherche de réseau et traçage des canalisations</h2>

      <p>
        Nos caméras sont équipées de sondes émettrices permettant de localiser
        précisément les canalisations depuis la surface, même sous le béton
        ou la terre.
      </p>

      <h3>Dans quels cas utiliser la recherche de réseau ?</h3>

      <ul class="inspection-list">
        <li>Retrouver une fosse septique ou un regard enterré</li>
        <li>Cartographier les réseaux avant des travaux</li>
        <li>Localiser précisément une casse ou une fuite</li>
        <li>Limiter la casse lors d'une réparation ciblée</li>
      </ul>

      <div class="inspection-intervention">
        <img
          src="<?= BASE_URL ?>/assets/img/ecran-video.jpg"
          alt="Inspection vidéo de canalisation par caméra SAHP">
        <p class="inspection-caption">
          Inspection vidéo des canalisations avec caméra haute définition
          et localisation précise du réseau.
        </p>
      </div>

      <h2>Les avantages de l'intervention SAHP</h2>

      <ul class="inspection-list">
        <li>Diagnostic rapide et précis</li>
        <li>Aucune destruction inutile</li>
        <li>Réduction importante des coûts de réparation</li>
        <li>Matériel professionnel haute définition</li>
      </ul>

      <h2>Quand faire appel à une inspection vidéo ?</h2>

      <ul class="inspection-list">
        <li>Bouchons récurrents malgré plusieurs débouchages</li>
        <li>Avant l'achat d'un bien immobilier</li>
        <li>Avant des travaux d'extension ou de rénovation</li>
        <li>Lors de la réception d'un chantier neuf</li>
      </ul>

      <p class="inspection-note">
        Conseil SAHP : une canalisation contrôlée régulièrement permet
        d'anticiper les pannes et d'éviter les urgences coûteuses.
      </p>

      <div class="page-pricing-wrapper">
        <div class="page-pricing-card pricing-category">
          <h3 class="category-title">
            Inspection Vidéo
          </h3>
          <div class="pricing-list">
            <div class="pricing-item">
              <span class="service-name">Inspection vidéo de contrôle rapide sans rapport</span>
              <span class="service-price" data-price-ht="190.00">190.00 € HT</span>
            </div>
            <div class="pricing-item">
              <span class="service-name">Inspection vidéo de canalisation + fourniture d'un rapport d'inspection</span>
              <span class="service-price" data-price-ht="590.00">590.00 € HT</span>
            </div>
            <div class="pricing-item">
              <span class="service-name">Inspection vidéo d'un réseau de canalisations</span>
              <span class="service-price">Sur devis</span>
            </div>
          </div>
          <div class="pricing-btn-wrapper">
            <a href="<?= BASE_URL ?>/devis" class="pricing-btn">Demander un devis gratuit</a>
          </div>
        </div>
      </div>

      <?php
      require_once APP_PATH . '/helpers/faq.php';
      sahp_render_faq([
        [
          'q' => 'À quoi sert concrètement une inspection vidéo ?',
          'a' => '<p>Elle permet de voir l’intérieur de la canalisation sans rien casser. On identifie la cause exacte d’un problème (bouchon, fissure, contre-pente, racines) et on agit ensuite au bon endroit, au lieu de creuser au hasard. C’est souvent ce qui évite des travaux inutiles et coûteux.</p>',
        ],
        [
          'q' => 'Le rapport d’inspection est-il accepté par les assurances ?',
          'a' => '<p>Oui. À l’issue de l’intervention, nous pouvons fournir un rapport vidéo avec images et commentaires. C’est une preuve objective souvent demandée en cas de dégât des eaux, de litige immobilier ou avant la réception d’un chantier.</p>',
        ],
        [
          'q' => 'Pouvez-vous localiser une canalisation enterrée ?',
          'a' => '<p>Nos caméras embarquent une sonde émettrice : on repère le tracé et la profondeur depuis la surface, même sous le béton ou la terre. Très utile pour retrouver une fosse, cartographier un réseau avant travaux ou cibler précisément une réparation.</p>',
        ],
        [
          'q' => 'Quand est-ce le bon moment pour faire contrôler ses canalisations ?',
          'a' => '<p>Idéalement avant l’achat d’un bien, avant des travaux, ou dès que les bouchons reviennent malgré plusieurs <a href="' . BASE_URL . '/debouchage">débouchages</a>. Un contrôle régulier permet d’anticiper les pannes plutôt que de subir une <a href="' . BASE_URL . '/urgence">urgence</a>.</p>',
        ],
        [
          'q' => 'Combien coûte une inspection caméra ?',
          'a' => '<p>Cela dépend du type d’intervention : simple contrôle visuel ou inspection complète avec rapport détaillé. Le mieux est de nous indiquer votre besoin via une <a href="' . BASE_URL . '/devis">demande de devis</a> ; on vous oriente vers la formule la plus adaptée.</p>',
        ],
      ]);
      ?>

    </div>
  </div>

</section>