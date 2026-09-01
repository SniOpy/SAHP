<section class="debouchage-page">

  <div class="debouchage-container">
    <div class="container-inner">

    <h1>Débouchage de canalisation : intervention rapide et durable</h1>

    <p class="debouchage-intro">
      Un évier qui ne se vide plus, des odeurs désagréables ou des WC qui débordent
      sont les signes d’une canalisation obstruée.
      SAHP intervient rapidement pour un débouchage efficace, sans produits chimiques.
      Retrouvez l'ensemble de nos prestations sur notre page
      <a href="<?= BASE_URL ?>/">assainissement en Île-de-France</a>.
    </p>

    <!-- IMAGE INTERVENTION -->
    <div class="debouchage-intervention">
      <img
        src="<?= BASE_URL ?>/assets/img/debouchage.jpg"
        alt="Canalisation bouchée avant intervention SAHP">
      <p class="debouchage-caption">
        Canalisation obstruée : une intervention professionnelle évite
        les dégâts et les récidives.
      </p>
    </div>

    <h2>Comment reconnaître une canalisation bouchée ?</h2>

    <ul class="debouchage-list">
      <li>Évacuation lente de l’eau dans l’évier, la douche ou le lavabo</li>
      <li>Bruits de gargouillis dans les canalisations</li>
      <li>Odeurs d’égout persistantes</li>
      <li>Remontées d’eau entre différents équipements</li>
    </ul>

    <p class="debouchage-note">
      Conseil SAHP : intervenir dès les premiers signes permet
      d’éviter l’obstruction totale et les dégâts des eaux.
    </p>

    <h2>Pourquoi éviter les produits chimiques ?</h2>

    <p>
      Les déboucheurs chimiques vendus dans le commerce sont agressifs
      pour vos canalisations et nocifs pour l’environnement.
      Ils percent souvent le bouchon sans le supprimer entièrement,
      ce qui entraîne une récidive rapide.
    </p>

    <p>
      Chez SAHP, nous privilégions des méthodes professionnelles,
      durables et respectueuses de vos installations.
    </p>

    <h2>Nos techniques de débouchage professionnel</h2>

    <h3>Inspection vidéo par caméra</h3>

    <p>
      Lorsque la situation l’exige, nous inspectons l’intérieur des canalisations
      afin d’identifier précisément l’origine du bouchon et l’état du réseau.
    </p>

    <h3>Débouchage haute pression et hydrocurage</h3>

    <p>
      Le jet d’eau haute pression permet de pulvériser les bouchons
      (graisses, lingettes, calcaire) et de nettoyer les parois,
      sans abîmer les conduits.
    </p>

    <h2>Pourquoi choisir SAHP pour le débouchage ?</h2>

    <ul class="debouchage-list">
      <li>Intervention rapide en Île-de-France</li>
      <li>Diagnostic précis avant action</li>
      <li>Équipements professionnels haute pression</li>
      <li>Respect des normes et de l’environnement</li>
      <li>Tarification claire et transparente</li>
    </ul>

    <div class="page-pricing-wrapper">
      <div class="page-pricing-card pricing-category">
        <h3 class="category-title">
          Débouchage de canalisation
        </h3>
        <div class="pricing-list">
          <div class="pricing-item">
            <span class="service-name">Débouchage / dégorgement de canalisation</span>
            <span class="service-price" data-price-ht="275.00">275.00 € HT</span>
          </div>
          <div class="pricing-item">
            <span class="service-name">Débouchage Vide Ordure</span>
            <span class="service-price" data-price-ht="250.00">250.00 € HT</span>
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
        'debouchage',
        'Débouchage canalisation dans le Val-de-Marne',
        'SAHP débouche vos canalisations dans le Val-de-Marne. Pages locales par commune :'
    );
    ?>

    <?php
    require_once APP_PATH . '/helpers/faq.php';
    sahp_render_faq([
      [
        'q' => 'Combien de temps faut-il pour déboucher une canalisation ?',
        'a' => '<p>Dans la grande majorité des cas, un bouchon domestique (évier, lavabo, douche, WC) est réglé en 30 minutes à 1 heure. Tout dépend de l’endroit où se trouve l’obstruction et de sa nature. Quand le bouchon est plus profond, sur une colonne d’immeuble par exemple, on passe à l’hydrocurage haute pression, qui reste rapide une fois le camion sur place.</p>',
      ],
      [
        'q' => 'Le débouchage haute pression abîme-t-il les tuyaux ?',
        'a' => '<p>Non. Nos techniciens règlent la pression et la buse selon le matériau et le diamètre du conduit. C’est justement plus sûr que les déboucheurs chimiques du commerce, qui attaquent les joints et le PVC. Si on a un doute sur l’état du réseau, on commence par une <a href="' . BASE_URL . '/inspection">inspection vidéo</a> avant d’intervener.</p>',
      ],
      [
        'q' => 'Que puis-je faire en attendant votre arrivée ?',
        'a' => '<p>Le réflexe le plus utile : arrêter d’utiliser le point d’eau concerné pour ne pas aggraver le débordement, et éviter de verser des produits chimiques (ils compliquent l’intervention et sont dangereux). Si l’eau remonte dans plusieurs pièces, coupez l’arrivée d’eau et appelez-nous : c’est typiquement une <a href="' . BASE_URL . '/urgence">urgence assainissement</a>.</p>',
      ],
      [
        'q' => 'Intervenez-vous le soir et le week-end ?',
        'a' => '<p>Oui, nos équipes sont mobilisables 24h/24 et 7j/7 en Île-de-France, y compris les jours fériés. Pour une intervention immédiate, le plus simple reste de nous appeler directement ; sinon vous pouvez aussi <a href="' . BASE_URL . '/devis">demander un devis</a> en ligne.</p>',
      ],
      [
        'q' => 'Comment éviter que le bouchon revienne ?',
        'a' => '<p>Quand les bouchons reviennent malgré les débouchages, c’est souvent que les parois sont encrassées en profondeur. Un <a href="' . BASE_URL . '/curage">curage haute pression</a> régulier remet la canalisation à son diamètre d’origine. Pour les réseaux sensibles ou les copropriétés, on conseille un entretien planifié plutôt que d’attendre la prochaine panne.</p>',
      ],
    ]);
    ?>

    </div>
  </div>

</section>