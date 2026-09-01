<section class="pompage-page">

  <div class="pompage-container">
    <div class="container-inner">

      <h1>Pompage et vidange : l'expertise SAHP pour un assainissement durable</h1>

      <p class="pompage-intro">
        Le pompage et la vidange sont essentiels pour garantir le bon fonctionnement
        de vos installations d'assainissement, prévenir les pannes et respecter
        les obligations sanitaires et environnementales.
      </p>

      <!-- IMAGE INTERVENTION -->
      <div class="pompage-intervention">
        <img
          src="<?= BASE_URL ?>/assets/img/pompage.jpg"
          alt="Intervention de pompage et vidange par camion hydrocureur SAHP">
        <p class="pompage-caption">
          Intervention de pompage et vidange réalisée par les équipes SAHP
        </p>
      </div>

      <h2>Pourquoi le pompage et la vidange sont-ils indispensables ?</h2>

      <p>
        Les systèmes d'assainissement, qu'ils soient collectifs ou individuels,
        accumulent naturellement des boues, des graisses et des déchets solides.
        Sans entretien régulier, ces dépôts provoquent des dysfonctionnements
        pouvant aller jusqu'au débordement.
      </p>

      <ul class="pompage-list">
        <li>Bouchons persistants dans les canalisations</li>
        <li>Mauvaises odeurs dans l'habitation ou le local professionnel</li>
        <li>Risques de pollution des sols et des eaux</li>
        <li>Dégradation prématurée des installations</li>
      </ul>

      <h2>Les prestations de pompage et vidange par SAHP</h2>

      <h3>Vidange de fosse septique et fosse toutes eaux</h3>

      <p>
        La vidange de fosse est recommandée lorsque le volume de boues atteint
        environ 50 % de la capacité de la cuve (en moyenne tous les 3 à 4 ans).
      </p>

      <ul class="pompage-list">
        <li>Pompage des boues et eaux usées</li>
        <li>Nettoyage des canalisations d'entrée et de sortie</li>
        <li>Contrôle du bon écoulement et du préfiltre</li>
      </ul>

      <h3>Pompage de bac à graisse</h3>

      <p>
        Obligatoire pour les restaurateurs et métiers de bouche, le bac à graisse
        doit être entretenu régulièrement pour éviter l'obstruction du réseau
        et les risques de sanctions administratives.
      </p>

      <h3>Pompage de postes de relevage, de puisards et de sous-sol</h3>

      <p>
        En cas de remontée d'eaux, d'inondation de sous-sol ou de saturation
        des installations, SAHP intervient rapidement pour le pompage
        des eaux claires ou chargées.
      </p>

      <div class="pompage-intervention2">
        <p style="text-align: center;">
          <img
            src="<?= BASE_URL ?>/assets/img/intervention-curage2.jpg"
            alt="Intervention de pompage et vidange par camion hydrocureur SAHP">
        </p>
        <p class="pompage-caption">
          Intervention de pompage et vidange réalisée par les équipes SAHP
          avec camion hydrocureur.
        </p>
      </div>

      <h2>Pourquoi choisir SAHP pour vos travaux de pompage ?</h2>

      <ul class="pompage-list">
        <li>Traitement des déchets en centre agréé avec remise d'un BSD</li>
        <li>Camions hydrocureurs puissants et adaptés à tous les volumes</li>
        <li>Devis clair avant toute intervention planifiée</li>
        <li>Intervention rapide en Île-de-France</li>
      </ul>

      <h2>Quand faut-il faire appel à un professionnel ?</h2>

      <ul class="pompage-list">
        <li>Écoulement lent des WC, douches ou éviers</li>
        <li>Bruits de gargouillis dans les canalisations</li>
        <li>Odeurs persistantes autour de la fosse ou dans le bâtiment</li>
      </ul>

      <p class="pompage-note">
        Conseil SAHP : évitez les produits chimiques corrosifs dans une fosse pleine.
        Ils détruisent les bactéries nécessaires au traitement des déchets.
        Un diagnostic professionnel est indispensable.
      </p>

      <div class="page-pricing-wrapper">
        <div class="page-pricing-card pricing-category">
          <h3 class="category-title">Pompage</h3>
          <div class="pricing-list">
            <div class="pricing-item">
              <span class="service-name">Fosse septique jusqu'à 1 m³</span>
              <span class="service-price" data-price-ht="279.00">279.00 € HT</span>
            </div>
            <div class="pricing-item">
              <span class="service-name">Bac à graisse jusqu'à 1 m³</span>
              <span class="service-price" data-price-ht="299.00">299.00 € HT</span>
            </div>
            <div class="pricing-item">
              <span class="service-name">Puisard jusqu'à 1 m³</span>
              <span class="service-price" data-price-ht="279.00">279.00 € HT</span>
            </div>
            <div class="pricing-item">
              <span class="service-name">Pompage m³ supplémentaire</span>
              <span class="service-price" data-price-ht="50.00">50.00 € HT</span>
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
          'pompage',
          'Pompage et vidange dans le Val-de-Marne',
          'Pompage de fosse septique, bac à graisse et assainissement dans le 94 :'
      );
      ?>

      <?php
      require_once APP_PATH . '/helpers/faq.php';
      sahp_render_faq([
        [
          'q' => 'Tous les combien faut-il vidanger une fosse septique ?',
          'a' => '<p>En moyenne tous les 3 à 4 ans, mais le vrai critère est le niveau de boues : on vidange quand elles atteignent environ la moitié de la cuve. Le nombre d’occupants et le volume de la fosse font varier ce rythme. Lors de l’intervention, on en profite pour contrôler le préfiltre et le bon écoulement.</p>',
        ],
        [
          'q' => 'Que faites-vous des déchets pompés ?',
          'a' => '<p>Les matières sont évacuées et traitées dans un centre agréé. Vous recevez un bordereau de suivi des déchets (BSD), qui prouve la conformité de la filière d’élimination. C’est un point important, en particulier pour les professionnels soumis à des contrôles.</p>',
        ],
        [
          'q' => 'Le pompage de bac à graisse est-il obligatoire ?',
          'a' => '<p>Pour les restaurants et les métiers de bouche, oui : un bac à graisse mal entretenu finit par boucher le réseau et peut entraîner des sanctions. Nous assurons le pompage, le nettoyage et la remise en eau, avec traçabilité. Pour un suivi régulier, un contrat de <a href="' . BASE_URL . '/maintenance-pro">maintenance professionnelle</a> est la solution la plus simple.</p>',
        ],
        [
          'q' => 'Pouvez-vous intervenir en urgence pour un sous-sol inondé ?',
          'a' => '<p>Oui. En cas de remontée d’eaux ou de saturation, on dépêche un camion pour le pompage des eaux claires ou chargées. Si c’est critique (inondation, débordement), passez par notre service <a href="' . BASE_URL . '/urgence">urgence 24h/7j</a> pour une prise en charge immédiate.</p>',
        ],
        [
          'q' => 'Comment savoir si ma fosse a besoin d’être pompée ?',
          'a' => '<p>Les signaux classiques : écoulements lents, gargouillis dans les canalisations et odeurs autour de la fosse ou dans le bâtiment. Au moindre doute, un diagnostic évite la mauvaise surprise du débordement. Vous pouvez nous <a href="' . BASE_URL . '/devis">demander un devis</a> ou nous appeler pour en parler.</p>',
        ],
      ]);
      ?>

    </div>
  </div>

</section>