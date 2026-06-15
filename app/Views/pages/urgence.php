<section class="urgence-page">

  <div class="urgence-container">
    <div class="container-inner">

    <h1>Urgence assainissement 24h/24 – 7j/7</h1>

    <p class="urgence-intro">
      Canalisation bouchée, fosse qui déborde, refoulement des eaux usées ?
      SAHP intervient en urgence, de jour comme de nuit, week-ends et jours fériés,
      pour limiter les dégâts et rétablir la situation rapidement.
    </p>

    <div class="urgence-alert">
      <p>
        🚨 Une urgence ne prévient jamais.<br>
        Plus l'intervention est rapide, plus les dégâts sont limités.
      </p>
    </div>

    <h2>Quand déclencher une intervention d'urgence ?</h2>

    <ul class="urgence-list">
      <li>Refoulement des eaux usées par les WC, douches ou éviers</li>
      <li>Canalisation totalement bouchée, sans aucun écoulement</li>
      <li>Débordement de fosse septique ou micro-station</li>
      <li>Rupture ou fuite visible de canalisation</li>
      <li>Panne de pompe de relevage avec risque d'inondation</li>
    </ul>

    <p class="urgence-note">
      Conseil SAHP : en cas de doute, coupez l'arrivée d'eau
      et contactez immédiatement un professionnel.
      Les produits chimiques peuvent aggraver la situation.
    </p>

    <h2>Pourquoi faire appel à SAHP en urgence ?</h2>

    <h3>Intervention rapide</h3>
    <p>
      Nos équipes sont mobilisables 24h/24.
      Nous dépêchons le camion hydrocureur le plus proche
      afin de réduire au maximum le temps d'attente.
    </p>

    <h3>Équipements professionnels</h3>
    <p>
      Chaque intervention d'urgence est réalisée avec du matériel adapté :
      hydrocurage haute pression, pompage, débouchage mécanique
      et inspection vidéo si nécessaire.
    </p>

    <h3>Transparence des prix</h3>
    <p>
      L'urgence ne doit pas rimer avec mauvaise surprise.
      Nos techniciens vous expliquent la solution et les coûts
      avant toute intervention lourde.
    </p>

    <h2>Déroulement d'une intervention d'urgence SAHP</h2>

    <ol class="urgence-steps">
      <li>Analyse de la situation par téléphone</li>
      <li>Arrivée rapide et sécurisation des lieux</li>
      <li>Diagnostic précis du problème</li>
      <li>Intervention immédiate (pompage, curage, débouchage)</li>
      <li>Contrôle final et remise en état</li>
    </ol>

    <h2>Prévenir les urgences futures</h2>

    <p>
      Après une intervention d'urgence, nos équipes peuvent vous proposer
      une solution préventive adaptée.
      Un entretien régulier permet d'éviter jusqu'à 80 % des urgences.
    </p>

    <div class="pricing-category">
      <h3 class="category-title">
        Débouchage de canalisation (Urgence)
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
      <a href="<?= BASE_URL ?>/devis" class="pricing-btn">Demander un devis gratuit</a>
      <a href="tel:0176242884" class="pricing-btn" style="background: #c62828; color: #ffffff; border-color: #c62828; margin-top: 15px;">
        📞 01 76 24 28 84 – Urgence 24/7
      </a>
    </div>

    <?php
    require_once APP_PATH . '/helpers/faq.php';
    sahp_render_faq([
      [
        'q' => 'En combien de temps arrivez-vous en cas d’urgence ?',
        'a' => '<p>Nous dépêchons l’équipe et le camion hydrocureur les plus proches pour réduire le délai au maximum. Le temps d’arrivée dépend de votre commune en Île-de-France et du trafic, mais l’urgence est traitée en priorité, de jour comme de nuit. Un premier diagnostic se fait souvent dès l’appel.</p>',
      ],
      [
        'q' => 'Que faire en attendant l’intervention ?',
        'a' => '<p>Coupez l’arrivée d’eau si vous le pouvez, n’utilisez plus les sanitaires concernés et mettez à l’abri ce qui pourrait être touché par un débordement. Surtout, évitez les produits chimiques : ils aggravent souvent la situation et rendent l’intervention plus délicate.</p>',
      ],
      [
        'q' => 'Intervenez-vous la nuit, le week-end et les jours fériés ?',
        'a' => '<p>Oui, notre service d’urgence fonctionne 24h/24 et 7j/7, week-ends et jours fériés compris. Une urgence ne prévient jamais : plus l’intervention est rapide, plus les dégâts (et la facture de remise en état) sont limités.</p>',
      ],
      [
        'q' => 'Les tarifs d’urgence sont-ils annoncés à l’avance ?',
        'a' => '<p>Oui. Nos techniciens vous expliquent la solution et le coût avant toute intervention lourde. L’urgence ne doit pas rimer avec mauvaise surprise. Pour les besoins non urgents, vous pouvez aussi <a href="' . BASE_URL . '/devis">demander un devis</a> tranquillement en ligne.</p>',
      ],
      [
        'q' => 'Comment éviter que l’urgence se reproduise ?',
        'a' => '<p>Après l’intervention, on vous propose si besoin une solution préventive : un <a href="' . BASE_URL . '/curage">curage</a> ou un contrôle par <a href="' . BASE_URL . '/inspection">inspection vidéo</a>. Un entretien régulier évite jusqu’à 80 % des urgences.</p>',
      ],
    ]);
    ?>

    </div>
  </div>

</section>