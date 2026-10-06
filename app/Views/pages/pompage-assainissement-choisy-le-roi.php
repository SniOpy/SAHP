<?php

/**
 * Page SEO locale : Pompage assainissement à Choisy-le-Roi (94).
 * Route : /pompage-assainissement-choisy-le-roi
 *
 * Réutilise le design des pages SEO locales (classes .vdm-*).
 * Maillage interne : routes réelles (/pompage, /debouchage, /curage, /inspection,
 * /urgence, /contact, /devis, /debouchage-canalisation-val-de-marne-94).
 *
 * Article Paroles de Pro associé :
 *   /paroles-de-pro/pompage-assainissement-choisy-le-roi-quand-intervenir
 */

$publicRoot = realpath(__DIR__ . '/../../../public') ?: '';

$heroUploadPath = $publicRoot . '/uploads/blog/pompage-assainissement-choisy-le-roi-1.jpg';
$heroImageSrc = ($publicRoot !== '' && is_file($heroUploadPath))
  ? BASE_URL . '/uploads/blog/pompage-assainissement-choisy-le-roi-1.jpg'
  : BASE_URL . '/assets/img/pompage.jpg';

$sectionUploadPath = $publicRoot . '/uploads/blog/pompage-assainissement-choisy-le-roi-2.jpg';
$sectionImageSrc = ($publicRoot !== '' && is_file($sectionUploadPath))
  ? BASE_URL . '/uploads/blog/pompage-assainissement-choisy-le-roi-2.jpg'
  : BASE_URL . '/assets/img/pompage2.jpg';

$choisyVilles = [
  ['nom' => 'Choisy-le-Roi', 'slug' => 'choisy-le-roi'],
  ['nom' => 'Créteil', 'slug' => 'creteil'],
  ['nom' => 'Vitry-sur-Seine', 'slug' => 'vitry-sur-seine'],
  ['nom' => 'Orly', 'slug' => 'orly'],
  ['nom' => 'Thiais', 'slug' => 'thiais'],
  ['nom' => 'Valenton', 'slug' => 'valenton'],
  ['nom' => 'Alfortville', 'slug' => 'alfortville'],
  ['nom' => 'Maisons-Alfort', 'slug' => 'maisons-alfort'],
  ['nom' => 'Bonneuil-sur-Marne', 'slug' => 'bonneuil-sur-marne'],
];
?>

<section class="vdm-page">
  <div class="vdm-container">
    <div class="container-inner">

      <!-- ===================== HERO ===================== -->
      <header class="vdm-hero">
        <h1>Pompage assainissement à Choisy-le-Roi</h1>

        <p class="vdm-subtitle">
          Intervention pour eaux usées, regard saturé, fosse, bac à graisse, vidange ou
          urgence assainissement. SAHP intervient à Choisy-le-Roi pour évacuer les
          liquides et matières accumulés dans votre réseau ou votre installation
          d'assainissement, avec un matériel professionnel et un tarif annoncé avant
          l'intervention.
        </p>

        <div class="vdm-cta-group">
          <a class="vdm-btn vdm-btn-call" href="tel:+33176242884">Appeler SAHP</a>
          <a class="vdm-btn vdm-btn-primary" href="<?= BASE_URL ?>/contact">Demander une intervention</a>
          <a class="vdm-btn vdm-btn-ghost" href="<?= BASE_URL ?>/devis">Demander un devis</a>
        </div>

        <div class="vdm-hero-visual">
          <img
            src="<?= $heroImageSrc ?>"
            alt="Pompage assainissement à Choisy-le-Roi par une entreprise spécialisée"
            loading="eager"
            decoding="async"
            width="1100"
            height="420" />
        </div>
      </header>

      <!-- ===================== H2 : PRÉSENTATION ===================== -->
      <h2>Une intervention de pompage assainissement à Choisy-le-Roi</h2>

      <p>
        Le <strong>pompage assainissement à Choisy-le-Roi</strong> consiste à évacuer
        les eaux usées, les boues, les graisses ou les matières accumulées dans une
        installation qui ne s'évacue plus correctement. Contrairement au simple
        débouchage, le pompage traite un volume de liquides ou de déchets déjà
        présents dans un regard, une fosse, un bac à graisse ou un réseau saturé.
      </p>

      <p>
        SAHP, <strong>entreprise de pompage assainissement dans le Val-de-Marne</strong>,
        intervient à Choisy-le-Roi pour les particuliers, les syndics, les
        copropriétés, les restaurants et les professionnels. Que ce soit pour un
        entretien planifié ou une <strong>urgence assainissement à Choisy-le-Roi</strong>,
        nos équipes arrivent avec un camion hydrocureur adapté au volume à traiter.
        Pour l'ensemble de nos prestations de
        <a href="<?= BASE_URL ?>/pompage">pompage et vidange</a>, nous restons votre
        interlocuteur direct sur le terrain.
      </p>

      <!-- ===================== H2 : QUAND ===================== -->
      <h2>Dans quels cas demander un pompage ?</h2>

      <p>
        Un <strong>pompage à Choisy-le-Roi</strong> s'impose dès que l'installation
        ne parvient plus à évacuer les eaux ou les matières. Les situations les plus
        fréquentes sont :
      </p>

      <ul class="vdm-list">
        <li><strong>regard plein</strong> ou inaccessible à cause du niveau d'eau ;</li>
        <li><strong>eaux usées qui remontent</strong> dans les WC, la douche ou l'évier ;</li>
        <li><strong>fosse septique saturée</strong> ou vidange en retard ;</li>
        <li><strong>bac à graisse rempli</strong> chez un restaurant ou un commerce ;</li>
        <li><strong>cave ou sous-sol</strong> avec eau stagnante à évacuer ;</li>
        <li><strong>réseau bouché avec stagnation</strong> en amont ou en aval ;</li>
        <li><strong>odeurs fortes</strong> persistantes autour d'une installation ;</li>
        <li><strong>installation qui ne s'évacue plus</strong> malgré les premiers gestes ;</li>
        <li><strong>urgence assainissement</strong> : refoulement, débordement ou risque de dégât des eaux.</li>
      </ul>

      <p class="vdm-note">
        En cas de refoulement actif, il s'agit d'une
        <a href="<?= BASE_URL ?>/urgence">urgence assainissement</a> : arrêtez d'utiliser
        les points d'eau concernés et contactez-nous sans attendre pour limiter les dégâts.
      </p>

      <!-- CTA milieu de page -->
      <div class="vdm-cta-banner">
        <p>Besoin d'un pompage à Choisy-le-Roi ?</p>
        <div class="vdm-cta-group">
          <a class="vdm-btn vdm-btn-call" href="tel:+33176242884">Appeler SAHP</a>
          <a class="vdm-btn vdm-btn-primary" href="<?= BASE_URL ?>/contact">Demander une intervention</a>
        </div>
      </div>

      <!-- ===================== H2 : COMPARATIF ===================== -->
      <h2>Pompage, vidange, débouchage ou curage : quelle différence ?</h2>

      <p>
        Toutes ces interventions concernent l'assainissement, mais elles ne répondent
        pas au même problème. Ce tableau vous aide à identifier la bonne solution :
      </p>

      <div class="choisy-table-wrapper">
        <table class="choisy-table">
          <thead>
            <tr>
              <th scope="col">Problème constaté</th>
              <th scope="col">Intervention recommandée</th>
              <th scope="col">Objectif</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Canalisation bouchée</td>
              <td><a href="<?= BASE_URL ?>/debouchage">Débouchage canalisation</a></td>
              <td>Rétablir l'écoulement</td>
            </tr>
            <tr>
              <td>Regard ou fosse rempli</td>
              <td><a href="<?= BASE_URL ?>/pompage">Pompage assainissement</a></td>
              <td>Évacuer les liquides ou matières</td>
            </tr>
            <tr>
              <td>Bac à graisse saturé</td>
              <td><a href="<?= BASE_URL ?>/pompage">Vidange / pompage</a></td>
              <td>Retirer graisses et résidus</td>
            </tr>
            <tr>
              <td>Réseau encrassé</td>
              <td><a href="<?= BASE_URL ?>/curage">Curage canalisation</a></td>
              <td>Nettoyer les parois</td>
            </tr>
            <tr>
              <td>Bouchon récurrent</td>
              <td><a href="<?= BASE_URL ?>/inspection">Inspection vidéo</a></td>
              <td>Identifier l'origine du problème</td>
            </tr>
          </tbody>
        </table>
      </div>

      <p>
        Après un pompage, un <a href="<?= BASE_URL ?>/curage">curage canalisation</a>
        ou une <a href="<?= BASE_URL ?>/inspection">inspection vidéo canalisation</a>
        peut être nécessaire si le réseau reste encrassé ou si le bouchon revient.
        Nous vous orientons vers la bonne suite après diagnostic sur place.
      </p>

      <!-- ===================== H2 : TYPES DE POMPAGE ===================== -->
      <h2>Pompage eaux usées, fosse, bac à graisse et regard</h2>

      <p>
        Selon votre installation, le type de <strong>pompage assainissement 94</strong>
        varie. Nos interventions à Choisy-le-Roi couvrent notamment :
      </p>

      <ul class="vdm-list">
        <li><strong>Pompage eaux usées</strong> : évacuation des eaux chargées ou stagnantes dans un réseau ou un local.</li>
        <li><strong>Pompage fosse septique</strong> et <strong>vidange</strong> de fosse toutes eaux lorsque le niveau de boues est trop élevé.</li>
        <li><strong>Pompage bac à graisse</strong> : retrait des graisses et résidus alimentaires chez les professionnels de bouche.</li>
        <li><strong>Pompage regard assainissement</strong> : vidange d'un regard plein ou obstrué sur le réseau enterré.</li>
        <li><strong>Pompage cave ou sous-sol</strong> : évacuation d'eau stagnante après infiltration ou remontée.</li>
        <li><strong>Pompage boues et matières</strong> : traitement des volumes importants avec camion adapté.</li>
      </ul>

      <div class="vdm-hero-visual">
        <img
          src="<?= $sectionImageSrc ?>"
          alt="Intervention de pompage eaux usées et assainissement à Choisy-le-Roi"
          loading="lazy"
          decoding="async"
          width="1100"
          height="420" />
      </div>

      <p class="vdm-note">
        Pour comprendre dans quelles situations le pompage est la bonne réponse,
        consultez notre article :
        <a href="<?= BASE_URL ?>/paroles-de-pro/pompage-assainissement-choisy-le-roi-quand-intervenir">dans quels cas demander un pompage assainissement à Choisy-le-Roi</a>.
      </p>

      <!-- ===================== H2 : CLIENTÈLES ===================== -->
      <h2>Pour les particuliers, professionnels et copropriétés</h2>

      <p>
        Le <strong>pompage assainissement Choisy-le-Roi</strong> concerne tous types de
        clients. Nous intervenons régulièrement pour :
      </p>

      <ul class="vdm-list">
        <li><strong>Particuliers</strong> : pavillons, maisons avec fosse ou raccordement réseau.</li>
        <li><strong>Syndics et copropriétés</strong> : regards communs, colonnes, locaux techniques.</li>
        <li><strong>Restaurants et commerces</strong> : bacs à graisse, cuisines professionnelles.</li>
        <li><strong>Locaux professionnels et entreprises</strong> : sites logistiques, ateliers, bureaux.</li>
        <li><strong>Collectivités</strong> lorsque le réseau ou les ouvrages le nécessitent.</li>
      </ul>

      <p>
        Pour les professionnels soumis à des contrôles, nous fournissons une traçabilité
        des déchets évacués. En cas de doute sur la cause d'un dysfonctionnement après
        pompage, un <a href="<?= BASE_URL ?>/debouchage">débouchage canalisation</a>
        ou un curage peut compléter l'intervention.
      </p>

      <!-- ===================== H2 : ZONE ===================== -->
      <h2>Intervention à Choisy-le-Roi et dans le Val-de-Marne</h2>

      <p>
        Basée à Valenton, SAHP intervient rapidement à Choisy-le-Roi et dans les
        communes voisines du 94. Nos équipes couvrent notamment :
      </p>

      <ul class="vdm-cities">
        <?php foreach ($choisyVilles as $ville): ?>
          <li><span><?= $ville['nom'] ?></span></li>
        <?php endforeach; ?>
      </ul>

      <p class="vdm-note">
        Vous cherchez une intervention dans tout le département ? Consultez notre page
        <a href="<?= BASE_URL ?>/debouchage-canalisation-val-de-marne-94">débouchage canalisation dans le Val-de-Marne</a>
        ou <a href="<?= BASE_URL ?>/contact">contactez SAHP</a> pour vérifier notre
        disponibilité près de chez vous.
      </p>

      <!-- ===================== H2 : PRIX ===================== -->
      <h2>Prix d'un pompage assainissement à Choisy-le-Roi</h2>

      <p>
        Il n'existe pas de tarif unique pour un pompage : le montant dépend de votre
        situation. Chez SAHP, le prix vous est annoncé avant l'intervention, sans
        surprise sur la facture. Les principaux facteurs qui font varier le coût d'un
        <strong>pompage assainissement à Choisy-le-Roi</strong> sont :
      </p>

      <ul class="vdm-list">
        <li>le <strong>volume à pomper</strong> ;</li>
        <li>le <strong>type d'installation</strong> (fosse, bac à graisse, regard, cave) ;</li>
        <li>l'<strong>accessibilité</strong> du camion et du point de pompage ;</li>
        <li>le <strong>caractère urgent</strong> de la demande ;</li>
        <li>le besoin éventuel d'un <strong>débouchage ou curage</strong> en complément ;</li>
        <li>le <strong>type de client</strong> : particulier, syndic ou professionnel ;</li>
        <li>la <strong>complexité du réseau</strong> ou de l'ouvrage à traiter.</li>
      </ul>

      <p>
        Pour une estimation adaptée à votre cas, le plus simple reste de
        <a href="<?= BASE_URL ?>/devis">demander un devis</a> : réponse rapide, sans engagement.
      </p>

      <!-- ===================== H2 : FAQ ===================== -->
      <?php
      require_once APP_PATH . '/helpers/faq.php';
      sahp_render_faq([
        [
          'q' => 'Quand demander un pompage assainissement à Choisy-le-Roi ?',
          'a' => '<p>Dès qu\'un regard est plein, que des eaux usées remontent, qu\'une fosse ou un bac à graisse est saturé, ou qu\'une cave contient de l\'eau stagnante. En cas de refoulement ou de débordement, il s\'agit d\'une <a href="' . BASE_URL . '/urgence">urgence assainissement</a> : contactez-nous sans attendre.</p>',
        ],
        [
          'q' => 'Quelle différence entre pompage et débouchage ?',
          'a' => '<p>Le <a href="' . BASE_URL . '/debouchage">débouchage canalisation</a> libère un conduit obstrué pour rétablir l\'écoulement. Le pompage évacue les liquides, boues ou matières accumulés dans une fosse, un regard, un bac à graisse ou un local. Les deux peuvent se compléter selon la situation.</p>',
        ],
        [
          'q' => 'Peut-on pomper un bac à graisse ?',
          'a' => '<p>Oui. Le <strong>pompage bac à graisse</strong> est une intervention courante pour les restaurants et commerces. Nous évacuons les graisses et résidus, puis remettons l\'installation en eau. Un entretien régulier évite les bouchons et les mauvaises odeurs.</p>',
        ],
        [
          'q' => 'Le pompage est-il adapté aux professionnels ?',
          'a' => '<p>Oui. Nous intervenons pour les syndics, copropriétés, restaurants, commerces et entreprises du Val-de-Marne. Les déchets pompés sont évacués vers des filières agréées, avec traçabilité lorsque nécessaire.</p>',
        ],
        [
          'q' => 'Faut-il faire un curage après un pompage ?',
          'a' => '<p>Pas systématiquement. Si le réseau est encrassé ou si les bouchons reviennent, un <a href="' . BASE_URL . '/curage">curage canalisation</a> ou une <a href="' . BASE_URL . '/inspection">inspection vidéo</a> peut être recommandé pour traiter la cause en profondeur.</p>',
        ],
        [
          'q' => 'Combien coûte un pompage assainissement à Choisy-le-Roi ?',
          'a' => '<p>Le tarif dépend du volume, du type d\'installation, de l\'accessibilité, de l\'urgence et d\'éventuels travaux complémentaires. Nous annonçons toujours le prix avant d\'intervenir : <a href="' . BASE_URL . '/devis">demandez un devis</a> pour une estimation adaptée.</p>',
        ],
      ], 'Questions fréquentes');
      ?>

      <!-- ===================== CTA FINAL ===================== -->
      <div class="vdm-cta-final">
        <h2>Intervention pompage à Choisy-le-Roi</h2>
        <p>
          Particulier, syndic ou professionnel : SAHP intervient à Choisy-le-Roi et
          dans le Val-de-Marne pour le pompage, la vidange et l'urgence assainissement.
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