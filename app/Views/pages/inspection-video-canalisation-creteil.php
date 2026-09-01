<?php

/**
 * Page SEO locale : Inspection vidéo canalisation à Créteil (94).
 * Route : /inspection-video-canalisation-creteil
 *
 * Réutilise le design des pages SEO locales existantes (classes .vdm-*),
 * + un complément spécifique pour le tableau comparatif
 * (public/assets/css/pages/inspection-video-canalisation-creteil.css).
 *
 * Maillage interne : on pointe vers les routes RÉELLES existantes
 * (/inspection, /debouchage, /curage, /pompage, /urgence, /contact, /devis,
 *  /debouchage-canalisation-val-de-marne-94).
 *
 * Lien article à activer plus tard (route dynamique /paroles-de-pro/{slug}) :
 *   /paroles-de-pro/inspection-video-canalisation-creteil-quand-controler
 *   -> voir le commentaire dans la section "Inspection vidéo après un débouchage".
 */

// Communes proches desservies autour de Créteil (Val-de-Marne).
$creteilVilles = [
    ['nom' => 'Créteil', 'slug' => 'creteil'],
    ['nom' => 'Maisons-Alfort', 'slug' => 'maisons-alfort'],
    ['nom' => 'Bonneuil-sur-Marne', 'slug' => 'bonneuil-sur-marne'],
    ['nom' => 'Alfortville', 'slug' => 'alfortville'],
    ['nom' => 'Saint-Maur-des-Fossés', 'slug' => 'saint-maur-des-fosses'],
    ['nom' => 'Choisy-le-Roi', 'slug' => 'choisy-le-roi'],
    ['nom' => 'Valenton', 'slug' => 'valenton'],
    ['nom' => 'Vitry-sur-Seine', 'slug' => 'vitry-sur-seine'],
];
?>

<section class="vdm-page">
  <div class="vdm-container">
    <div class="container-inner">

      <!-- ===================== HERO ===================== -->
      <header class="vdm-hero">
        <h1>Inspection vidéo canalisation à Créteil</h1>

        <p class="vdm-subtitle">
          Diagnostic caméra pour canalisation bouchée, problème récurrent, mauvaises
          odeurs ou défaut de réseau. SAHP réalise l'inspection vidéo de vos
          canalisations à Créteil pour localiser précisément l'origine du problème,
          sans casser inutilement.
        </p>

        <div class="vdm-cta-group">
          <a class="vdm-btn vdm-btn-call" href="tel:+33176242884">Appeler SAHP</a>
          <a class="vdm-btn vdm-btn-primary" href="<?= BASE_URL ?>/contact">Demander une inspection vidéo</a>
          <a class="vdm-btn vdm-btn-ghost" href="<?= BASE_URL ?>/devis">Demander un devis</a>
        </div>

        <div class="vdm-hero-visual">
          <img
            src="<?= BASE_URL ?>/assets/img/inspection-video.jpg"
            alt="Inspection vidéo canalisation à Créteil avec caméra professionnelle"
            loading="eager"
            decoding="async"
            width="1100"
            height="420" />
        </div>
      </header>

      <!-- ===================== H2 : DIAGNOSTIC ===================== -->
      <h2>Besoin d'un diagnostic canalisation à Créteil ?</h2>

      <p>
        Un bouchon qui revient sans cesse, un évier qui se vide au ralenti, des
        mauvaises odeurs d'égout qui persistent malgré le nettoyage : à Créteil,
        ces signes cachent souvent un problème plus profond qu'un simple bouchon de
        surface. Tant que l'on ne voit pas l'intérieur du conduit, on agit à l'aveugle
        et l'on risque des travaux inutiles. L'<strong>inspection vidéo canalisation à
        Créteil</strong> répond exactement à ce besoin : une caméra étanche parcourt
        le réseau et montre, en direct, l'état réel de vos canalisations.
      </p>

      <p>
        Cette <strong>inspection caméra canalisation à Créteil</strong> est utile
        aussi bien dans les pavillons des quartiers résidentiels que dans les
        immeubles et copropriétés, où une colonne commune peut concerner plusieurs
        logements. Le but d'un <strong>diagnostic canalisation à Créteil</strong> est
        simple : identifier la cause exacte d'une <strong>canalisation bouchée à
        Créteil</strong> avant d'engager la moindre réparation. Pour les défauts de
        réseau de fond, SAHP s'appuie sur son savoir-faire en
        <a href="<?= BASE_URL ?>/inspection">inspection vidéo canalisation</a>.
      </p>

      <!-- ===================== H2 : QUAND ===================== -->
      <h2>Quand faire une inspection vidéo canalisation ?</h2>

      <p>
        Tous les bouchons ne nécessitent pas une caméra. En revanche, une
        <strong>recherche de bouchon canalisation à Créteil</strong> par inspection
        vidéo s'impose dès qu'un problème se répète ou qu'un doute existe sur l'état
        du réseau. Les situations les plus fréquentes sont :
      </p>

      <ul class="vdm-list">
        <li>un <strong>bouchon qui revient régulièrement</strong> au même endroit ;</li>
        <li>un <strong>écoulement lent</strong> qui persiste après un débouchage ;</li>
        <li>des <strong>mauvaises odeurs persistantes</strong> malgré le nettoyage ;</li>
        <li>des <strong>remontées d'eau</strong> d'un équipement à l'autre ;</li>
        <li>une <strong>suspicion de canalisation cassée</strong> ou fissurée ;</li>
        <li><strong>avant des travaux</strong>, un achat immobilier ou une rénovation ;</li>
        <li><strong>après un dégât des eaux</strong>, pour documenter l'origine ;</li>
        <li>un <strong>problème en copropriété ou dans un commerce</strong> nécessitant une preuve.</li>
      </ul>

      <p class="vdm-note">
        En cas de débordement ou de refoulement d'eaux usées, il s'agit d'une
        <a href="<?= BASE_URL ?>/urgence">urgence assainissement</a> : mieux vaut nous
        appeler sans attendre, l'inspection vidéo viendra ensuite confirmer la cause.
      </p>

      <!-- ===================== H2 : PROBLÈMES DÉTECTÉS ===================== -->
      <h2>Quels problèmes peut révéler une caméra d'inspection ?</h2>

      <p>
        La force d'une <strong>caméra canalisation à Créteil</strong> est de montrer
        ce qui reste invisible depuis la surface. En remontant le conduit, le
        technicien repère et localise précisément :
      </p>

      <ul class="vdm-list">
        <li><strong>Bouchon profond</strong> : amas compact loin du point d'accès, impossible à voir autrement.</li>
        <li><strong>Racines</strong> : intrusions par les joints qui freinent l'écoulement et retiennent les déchets.</li>
        <li><strong>Fissure</strong> : micro-cassure laissant entrer terre et racines.</li>
        <li><strong>Casse</strong> : canalisation rompue, souvent à l'origine de bouchons à répétition.</li>
        <li><strong>Contre-pente</strong> : défaut de pente où l'eau stagne au lieu de s'évacuer.</li>
        <li><strong>Affaissement</strong> : conduit déformé ou écrasé qui réduit le diamètre.</li>
        <li><strong>Objet coincé</strong> : corps étranger tombé dans le réseau.</li>
        <li><strong>Dépôt important de graisse ou de calcaire</strong> qui encrasse les parois.</li>
      </ul>

      <p>
        Grâce à la sonde émettrice intégrée à la caméra, SAHP peut aussi localiser le
        tracé et la profondeur du défaut depuis la surface. Résultat : une réparation
        ciblée, au bon endroit, qui évite de casser tout un sol.
      </p>

      <!-- CTA milieu de page -->
      <div class="vdm-cta-banner">
        <p>Un doute sur l'état d'une canalisation à Créteil ?</p>
        <div class="vdm-cta-group">
          <a class="vdm-btn vdm-btn-call" href="tel:+33176242884">Appeler SAHP</a>
          <a class="vdm-btn vdm-btn-primary" href="<?= BASE_URL ?>/contact">Faire contrôler une canalisation</a>
        </div>
      </div>

      <!-- ===================== H2 : APRÈS DÉBOUCHAGE ===================== -->
      <h2>Inspection vidéo après un débouchage canalisation</h2>

      <p>
        Un <a href="<?= BASE_URL ?>/debouchage">débouchage canalisation</a> rétablit
        l'écoulement, mais ne dit pas toujours <em>pourquoi</em> le bouchon s'est
        formé. Quand la <strong>canalisation se bouche régulièrement à Créteil</strong>,
        c'est le signe que la cause est encore présente : racine, casse, contre-pente
        ou dépôt installé sur toute la longueur. L'inspection vidéo intervient alors
        juste après le débouchage pour vérifier l'intérieur du conduit et confirmer
        que rien ne subsiste.
      </p>

      <p>
        Cette étape évite le cercle vicieux du « on débouche, ça revient ». En
        identifiant la vraie origine, on choisit la bonne suite : un simple
        <a href="<?= BASE_URL ?>/curage">curage canalisation</a> si les parois sont
        encrassées, ou une réparation ciblée en cas de défaut structurel.
      </p>

      <div class="vdm-hero-visual">
        <img
          src="<?= BASE_URL ?>/assets/img/ecran-video.jpg"
          alt="Contrôle caméra d'une canalisation après débouchage à Créteil"
          loading="lazy"
          decoding="async"
          width="1100"
          height="420" />
      </div>

      <?php /* Lien article Paroles de Pro (publié en prod) */ ?>
      <p class="vdm-note">
        À lire aussi :
        <a href="<?= BASE_URL ?>/paroles-de-pro/inspection-video-canalisation-creteil-quand-controler">quand contrôler une canalisation à Créteil</a>.
      </p>

      <!-- ===================== H2 : QUELLE SOLUTION ===================== -->
      <h2>Débouchage, curage ou inspection vidéo : quelle solution choisir ?</h2>

      <p>
        Chaque situation appelle l'intervention la plus adaptée. Ce tableau résume les
        cas les plus courants pour vous aider à y voir clair :
      </p>

      <div class="creteil-table-wrapper">
        <table class="creteil-table">
          <thead>
            <tr>
              <th scope="col">Situation</th>
              <th scope="col">Intervention recommandée</th>
              <th scope="col">Objectif</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Bouchon ponctuel</td>
              <td><a href="<?= BASE_URL ?>/debouchage">Débouchage canalisation</a></td>
              <td>Rétablir l'écoulement</td>
            </tr>
            <tr>
              <td>Bouchon récurrent</td>
              <td><a href="<?= BASE_URL ?>/inspection">Inspection vidéo</a></td>
              <td>Identifier la cause</td>
            </tr>
            <tr>
              <td>Canalisation encrassée</td>
              <td><a href="<?= BASE_URL ?>/curage">Curage canalisation</a></td>
              <td>Nettoyer les parois</td>
            </tr>
            <tr>
              <td>Réseau très chargé</td>
              <td><a href="<?= BASE_URL ?>/curage">Hydrocurage</a></td>
              <td>Nettoyer à haute pression</td>
            </tr>
            <tr>
              <td>Installation saturée</td>
              <td><a href="<?= BASE_URL ?>/pompage">Pompage et vidange</a></td>
              <td>Évacuer les matières</td>
            </tr>
          </tbody>
        </table>
      </div>

      <p>
        En cas de doute, l'inspection vidéo reste la solution la plus sûre : elle
        oriente vers la bonne intervention et évite les travaux inutiles, qu'il
        s'agisse d'un particulier, d'un syndic, d'une copropriété, d'un commerce,
        d'un restaurant ou d'une entreprise.
      </p>

      <!-- ===================== H2 : ZONE INTERVENTION ===================== -->
      <h2>Intervention SAHP à Créteil et dans le Val-de-Marne</h2>

      <p>
        Depuis sa base de Valenton, SAHP intervient rapidement à Créteil et dans tout
        le secteur. Nos équipes réalisent l'<strong>inspection vidéo canalisation dans
        le Val-de-Marne</strong> et l'<strong>inspection vidéo canalisation 94</strong>
        aussi bien pour les particuliers que pour les syndics, copropriétés, commerces,
        restaurants et entreprises. Communes proches desservies :
      </p>

      <ul class="vdm-cities">
        <?php foreach ($creteilVilles as $ville): ?>
          <li><span><?= $ville['nom'] ?></span></li>
        <?php endforeach; ?>
      </ul>

      <p class="vdm-note">
        Votre commune n'apparaît pas dans la liste ? Nous couvrons l'ensemble du
        département : pour une intervention plus large, consultez notre page
        <a href="<?= BASE_URL ?>/debouchage-canalisation-val-de-marne-94">débouchage canalisation dans le Val-de-Marne</a>
        ou <a href="<?= BASE_URL ?>/contact">contactez SAHP</a>.
      </p>

      <!-- ===================== H2 : PRIX ===================== -->
      <h2>Prix d'une inspection vidéo canalisation à Créteil</h2>

      <p>
        Il n'existe pas de prix unique pour une inspection vidéo : le tarif dépend de
        votre situation. Chez SAHP, le montant vous est annoncé avant l'intervention,
        sans surprise. Les principaux facteurs qui font varier le coût d'une
        <strong>inspection vidéo canalisation à Créteil</strong> sont :
      </p>

      <ul class="vdm-list">
        <li>l'<strong>accessibilité</strong> du réseau et des points de contrôle ;</li>
        <li>la <strong>longueur de canalisation</strong> à inspecter ;</li>
        <li>la <strong>complexité du réseau</strong> (nombre de branches, profondeur) ;</li>
        <li>le besoin éventuel d'un <strong>débouchage avant caméra</strong> ;</li>
        <li>le <strong>caractère urgent</strong> de la demande ;</li>
        <li>le <strong>type de client</strong> : particulier, syndic ou professionnel.</li>
      </ul>

      <p>
        Le plus fiable reste une estimation adaptée à votre cas :
        <a href="<?= BASE_URL ?>/devis">demander un devis</a> ne prend que quelques
        minutes et reste sans engagement.
      </p>

      <!-- ===================== H2 : FAQ ===================== -->
      <?php
      require_once APP_PATH . '/helpers/faq.php';
      sahp_render_faq([
        [
          'q' => 'Quand faire une inspection vidéo canalisation à Créteil ?',
          'a' => '<p>Dès qu\'un bouchon revient régulièrement, que l\'écoulement reste lent après un débouchage, que des odeurs persistent ou qu\'on suspecte une casse. C\'est aussi utile avant des travaux, après un dégât des eaux ou pour un problème en copropriété. La caméra localise la cause sans casser le sol.</p>',
        ],
        [
          'q' => 'Une caméra est-elle nécessaire après un débouchage ?',
          'a' => '<p>Pas systématiquement. Pour un bouchon simple, le <a href="' . BASE_URL . '/debouchage">débouchage</a> suffit. L\'inspection vidéo devient utile quand le bouchon récidive ou qu\'on veut vérifier qu\'il ne reste aucun défaut (racine, casse, contre-pente) après l\'intervention.</p>',
        ],
        [
          'q' => 'Que peut détecter une inspection vidéo canalisation ?',
          'a' => '<p>Un bouchon profond, des racines, une fissure, une casse, une contre-pente, un affaissement, un objet coincé ou un dépôt important de graisse ou de calcaire. La sonde permet aussi de localiser le tracé et la profondeur du défaut depuis la surface.</p>',
        ],
        [
          'q' => 'Quelle est la différence entre inspection vidéo et curage ?',
          'a' => '<p>L\'inspection vidéo <em>diagnostique</em> : elle montre l\'état réel du conduit et identifie la cause. Le <a href="' . BASE_URL . '/curage">curage</a> <em>nettoie</em> : il décolle graisses, tartre et dépôts sur toute la longueur. On contrôle d\'abord à la caméra, puis on cure si les parois sont encrassées.</p>',
        ],
        [
          'q' => 'SAHP intervient-il pour les copropriétés à Créteil ?',
          'a' => '<p>Oui. Nous avons l\'habitude des copropriétés, syndics, commerces et restaurants de Créteil et du Val-de-Marne. L\'inspection vidéo est particulièrement utile sur les colonnes communes et fournit une preuve objective, souvent demandée en cas de litige ou de dégât des eaux.</p>',
        ],
        [
          'q' => 'Combien coûte une inspection vidéo canalisation à Créteil ?',
          'a' => '<p>Le prix dépend de l\'accessibilité, de la longueur à contrôler, de la complexité du réseau, d\'un éventuel débouchage préalable, de l\'urgence et du type de client. Nous annonçons toujours le tarif avant d\'intervenir : le plus simple est de <a href="' . BASE_URL . '/devis">demander un devis</a>.</p>',
        ],
      ], 'Questions fréquentes');
      ?>

      <!-- ===================== CTA FINAL ===================== -->
      <div class="vdm-cta-final">
        <h2>Faites contrôler vos canalisations à Créteil</h2>
        <p>
          Particulier, syndic ou professionnel : SAHP réalise l'inspection vidéo de
          vos canalisations à Créteil et dans le Val-de-Marne pour localiser le
          problème et choisir la bonne intervention.
        </p>
        <div class="vdm-cta-group">
          <a class="vdm-btn vdm-btn-call" href="tel:+33176242884">Appeler SAHP</a>
          <a class="vdm-btn vdm-btn-primary" href="<?= BASE_URL ?>/contact">Contacter SAHP</a>
          <a class="vdm-btn vdm-btn-ghost" href="<?= BASE_URL ?>/devis">Demander un devis</a>
        </div>
      </div>

    </div>
  </div>
</section>
