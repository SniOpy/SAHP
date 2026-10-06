<?php

/**
 * Page SEO locale : Débouchage canalisation dans le Val-de-Marne (94).
 * Route : /debouchage-canalisation-val-de-marne-94
 *
 * Maillage interne : on pointe vers les routes RÉELLES existantes
 * (/debouchage, /curage, /inspection, /pompage, /urgence, /devis, /contact).
 *
 * Slugs SEO services à activer plus tard (pages non encore créées) :
 *   /debouchage-canalisation, /curage-canalisation, /hydrocurage-canalisation,
 *   /inspection-video-canalisation, /vidange-fosse-septique, /pompage-assainissement
 */

// Villes desservies. Le champ "slug" prepare les futures pages villes
// (ex. /debouchage-canalisation-valenton) : il suffira de transformer chaque
// ville en lien vers /debouchage-canalisation-{slug} une fois les pages creees.
$vdmVilles = [
    ['nom' => 'Valenton', 'slug' => 'valenton'],
    ['nom' => 'Orly', 'slug' => 'orly'],
    ['nom' => 'Créteil', 'slug' => 'creteil'],
    ['nom' => 'Choisy-le-Roi', 'slug' => 'choisy-le-roi'],
    ['nom' => 'Villeneuve-Saint-Georges', 'slug' => 'villeneuve-saint-georges'],
    ['nom' => 'Limeil-Brévannes', 'slug' => 'limeil-brevannes'],
    ['nom' => 'Bonneuil-sur-Marne', 'slug' => 'bonneuil-sur-marne'],
    ['nom' => 'Vitry-sur-Seine', 'slug' => 'vitry-sur-seine'],
    ['nom' => 'Ivry-sur-Seine', 'slug' => 'ivry-sur-seine'],
    ['nom' => 'Maisons-Alfort', 'slug' => 'maisons-alfort'],
    ['nom' => 'Villejuif', 'slug' => 'villejuif'],
    ['nom' => 'Alfortville', 'slug' => 'alfortville'],
    ['nom' => 'Champigny-sur-Marne', 'slug' => 'champigny-sur-marne'],
    ['nom' => 'Saint-Maur-des-Fossés', 'slug' => 'saint-maur-des-fosses'],
    ['nom' => 'Fontenay-sous-Bois', 'slug' => 'fontenay-sous-bois'],
];
?>

<section class="vdm-page">
  <div class="vdm-container">
    <div class="container-inner">

      <!-- ===================== HERO ===================== -->
      <header class="vdm-hero">
        <h1>Débouchage canalisation dans le Val-de-Marne 94</h1>

        <p class="vdm-subtitle">
          Canalisation bouchée dans le Val-de-Marne ? SAHP, entreprise d'assainissement
          basée à Valenton, intervient pour le débouchage de vos WC, éviers, douches,
          colonnes d'immeuble et égouts dans tout le 94. Diagnostic clair, matériel
          professionnel et prix annoncé avant l'intervention.
        </p>

        <div class="vdm-cta-group">
          <a class="vdm-btn vdm-btn-call" href="tel:+33176242884">Appeler SAHP</a>
          <a class="vdm-btn vdm-btn-primary" href="<?= BASE_URL ?>/contact">Demander une intervention</a>
          <a class="vdm-btn vdm-btn-ghost" href="<?= BASE_URL ?>/devis">Obtenir un devis</a>
        </div>

        <div class="vdm-hero-visual">
          <img
            src="<?= BASE_URL ?>/assets/img/blog/debouchage-canalisation.png"
            alt="Camion d'assainissement pour débouchage canalisation dans le Val-de-Marne"
            loading="eager"
            decoding="async"
            width="1100"
            height="420" />
        </div>
      </header>

      <!-- ===================== H2 : SYMPTÔMES ===================== -->
      <h2>Canalisation bouchée dans le Val-de-Marne ?</h2>

      <p>
        Une canalisation bouchée ne prévient jamais au bon moment. Dans le Val-de-Marne,
        nos équipes interviennent aussi bien dans les pavillons de Limeil-Brévannes ou
        Bonneuil-sur-Marne que dans les immeubles de Créteil, Vitry-sur-Seine ou
        Ivry-sur-Seine, où une colonne obstruée peut toucher plusieurs logements d'un coup.
        Plus on attend, plus le bouchon se compacte et plus le risque de dégât des eaux
        augmente.
      </p>

      <p>Certains signes doivent vous alerter avant l'obstruction totale :</p>

      <ul class="vdm-list">
        <li><strong>Écoulements lents</strong> dans l'évier, le lavabo, la douche ou la baignoire</li>
        <li><strong>Mauvaises odeurs</strong> d'égout qui remontent par les bondes</li>
        <li><strong>Bruits de gargouillis</strong> dans les tuyaux après chaque utilisation</li>
        <li><strong>Remontées d'eau</strong> d'un équipement à l'autre (l'eau des WC remonte dans la douche, par exemple)</li>
        <li><strong>Niveau d'eau anormal</strong> dans la cuvette des toilettes</li>
      </ul>

      <p class="vdm-note">
        Dès les premiers signes, mieux vaut faire intervenir un
        <a href="<?= BASE_URL ?>/debouchage">déboucheur de canalisation</a> professionnel :
        une canalisation bouchée prise tôt se traite vite, alors qu'un bouchon ignoré finit
        souvent en intervention d'urgence.
      </p>

      <!-- ===================== H2 : TYPES DE DÉBOUCHAGE ===================== -->
      <h2>Débouchage WC, évier, douche, baignoire et égout</h2>

      <p>
        Chaque point d'eau a ses propres causes de bouchon. Nos interventions de
        débouchage canalisation dans le 94 couvrent l'ensemble des installations
        des particuliers comme des professionnels :
      </p>

      <ul class="vdm-list">
        <li>
          <strong>Débouchage WC dans le Val-de-Marne</strong> : lingettes, excès de papier
          ou objet tombé dans la cuvette. On débouche les WC sans casser ni déposer la
          cuvette dans la majorité des cas.
        </li>
        <li>
          <strong>Évier et lavabo</strong> : graisses de cuisine, résidus alimentaires et
          dépôts de savon qui réduisent peu à peu le diamètre du tuyau.
        </li>
        <li>
          <strong>Douche et baignoire</strong> : cheveux et savon forment des bouchons
          tenaces au niveau du siphon et de l'évacuation.
        </li>
        <li>
          <strong>Colonne d'immeuble</strong> : un bouchon en partie collective qui
          provoque des remontées dans plusieurs appartements.
        </li>
        <li>
          <strong>Égout et regard extérieur</strong> : racines, boues et accumulation de
          graisses sur le réseau enterré.
        </li>
      </ul>

      <p>
        Selon la situation, nos techniciens utilisent le furet électromécanique pour
        un bouchon localisé, ou passent au jet haute pression pour les obstructions
        profondes. Nous évitons les déboucheurs chimiques du commerce, agressifs pour
        les joints et le PVC, et peu efficaces sur un bouchon réellement formé.
      </p>

      <!-- CTA milieu de page -->
      <div class="vdm-cta-banner">
        <p>Une canalisation bouchée chez vous dans le 94 ?</p>
        <div class="vdm-cta-group">
          <a class="vdm-btn vdm-btn-call" href="tel:+33176242884">Appeler SAHP</a>
          <a class="vdm-btn vdm-btn-primary" href="<?= BASE_URL ?>/contact">Demander une intervention</a>
        </div>
      </div>

      <!-- ===================== H2 : URGENCE ===================== -->
      <h2>Intervention en urgence dans le 94</h2>

      <p>
        Refoulement d'eaux usées, WC qui débordent, sous-sol inondé : ces situations ne
        peuvent pas attendre. SAHP propose un service d'<a href="<?= BASE_URL ?>/urgence">urgence
        débouchage canalisation</a> mobilisable 24h/24 et 7j/7 dans le Val-de-Marne, y compris
        les soirs, week-ends et jours fériés.
      </p>

      <p>
        En attendant l'arrivée de notre équipe, le réflexe le plus utile est d'arrêter
        d'utiliser le point d'eau concerné pour ne pas aggraver le débordement, et de ne
        verser aucun produit chimique. Si l'eau remonte dans plusieurs pièces, coupez
        l'arrivée d'eau et appelez-nous directement : nous priorisons les situations où
        chaque heure d'attente aggrave les dégâts.
      </p>

      <!-- ===================== H2 : CURAGE / HYDROCURAGE / INSPECTION ===================== -->
      <h2>Curage, hydrocurage et inspection vidéo</h2>

      <p>
        Quand les bouchons reviennent malgré plusieurs débouchages, le problème est
        souvent structurel : les parois de la canalisation sont encrassées sur toute
        leur longueur. On passe alors au <a href="<?= BASE_URL ?>/curage">curage de
        canalisation</a> par hydrocurage haute pression. Le jet d'eau décolle graisses,
        tartre et boues, et restitue le diamètre d'origine du conduit, sans produit chimique.
      </p>

      <p>
        L'<strong>hydrocurage de canalisation dans le Val-de-Marne</strong> est
        particulièrement adapté aux réseaux des restaurants, copropriétés et collectivités
        soumis à forte charge. Pour localiser précisément un défaut (canalisation cassée,
        contre-pente, racine, objet coincé) sans casser le sol, nous réalisons une
        <a href="<?= BASE_URL ?>/inspection">inspection vidéo de canalisation</a> par caméra,
        avec un diagnostic clair avant tout travaux. À Créteil, consultez notre page
        dédiée à l'<a href="<?= BASE_URL ?>/inspection-video-canalisation-creteil">inspection vidéo canalisation à Créteil</a>.
      </p>

      <p>
        Nous assurons aussi le <a href="<?= BASE_URL ?>/pompage">pompage et la vidange</a>
        de fosses septiques et bacs à graisse, en complément du débouchage lorsque la
        cause vient d'une installation pleine ou saturée.
      </p>

      <!-- ===================== H2 : PRIX ===================== -->
      <h2>Prix d'un débouchage canalisation dans le Val-de-Marne</h2>

      <p>
        Le prix d'un débouchage de canalisation dans le 94 dépend de plusieurs facteurs.
        Chez SAHP, le tarif vous est toujours annoncé avant l'intervention, sans surprise
        sur la facture. Les principaux éléments qui font varier le coût sont :
      </p>

      <ul class="vdm-list">
        <li>La <strong>nature du bouchon</strong> (graisses, lingettes, racines, calcaire)</li>
        <li>L'<strong>emplacement</strong> et l'accessibilité de la canalisation</li>
        <li>La <strong>technique nécessaire</strong> : furet, haute pression ou hydrocurage</li>
        <li>Le besoin éventuel d'une <strong>inspection vidéo</strong> pour localiser le défaut</li>
        <li>Le <strong>caractère urgent</strong> de l'intervention (nuit, week-end, jour férié)</li>
      </ul>

      <p>
        À titre indicatif, voici des tarifs de référence pour le débouchage. Pour une
        situation précise, le plus fiable reste une <a href="<?= BASE_URL ?>/devis">demande
        de devis gratuit</a>.
      </p>

      <div class="page-pricing-wrapper">
        <div class="page-pricing-card pricing-category">
          <div class="pricing-ttc-bar">
            <h3 class="pricing-ttc-title">Tarifs</h3>
          </div>
          <div class="pricing-list">
            <div class="pricing-item">
              <span class="service-name">Débouchage / dégorgement de canalisation</span>
              <span class="service-price" data-price-ht="275.00">275.00 € HT</span>
            </div>
            <div class="pricing-item">
              <span class="service-name">Débouchage vide-ordures</span>
              <span class="service-price" data-price-ht="250.00">250.00 € HT</span>
            </div>
            <div class="pricing-item">
              <span class="service-name">Curage / hydrocurage de canalisation</span>
              <span class="service-price">Sur devis</span>
            </div>
          </div>
          <div class="pricing-btn-wrapper">
            <a href="<?= BASE_URL ?>/devis" class="pricing-btn">Demander un devis gratuit</a>
          </div>
        </div>
      </div>

      <!-- ===================== H2 : INTERVENTIONS LOCALES 94 ===================== -->
      <?php
      require_once APP_PATH . '/helpers/local_pages.php';
      sahp_render_zone_local_links(
          'val-de-marne',
          'Nos interventions dans le Val-de-Marne',
          'SAHP intervient dans le Val-de-Marne pour le débouchage, le curage, l\'hydrocurage, l\'inspection vidéo, le pompage et les urgences d\'assainissement. Retrouvez nos pages dédiées par commune :'
      );
      ?>

      <!-- ===================== H2 : VILLES ===================== -->
      <h2>Villes desservies dans le Val-de-Marne</h2>

      <p>
        SAHP intervient pour le débouchage de canalisation dans l'ensemble du
        Val-de-Marne. Depuis notre base de Valenton, nous rejoignons rapidement les
        principales communes du 94 :
      </p>

      <ul class="vdm-cities">
        <?php foreach ($vdmVilles as $ville): ?>
          <!-- Futur lien ville : <a href="<?= BASE_URL ?>/debouchage-canalisation-<?= $ville['slug'] ?>"><?= $ville['nom'] ?></a> -->
          <li><span><?= $ville['nom'] ?></span></li>
        <?php endforeach; ?>
      </ul>

      <p class="vdm-note">
        Votre commune n'apparaît pas dans la liste ? Nous couvrons tout le département :
        <a href="<?= BASE_URL ?>/contact">contactez-nous</a> pour vérifier la disponibilité
        d'une équipe près de chez vous.
      </p>

      <!-- ===================== H2 : SERVICES LIÉS ===================== -->
      <h2>Pourquoi faire appel à SAHP ?</h2>

      <p>
        Entreprise familiale d'assainissement basée à Valenton depuis 2015, SAHP est un
        acteur de proximité dans le Val-de-Marne. Faire appel à nos équipes, c'est
        bénéficier de :
      </p>

      <ul class="vdm-list">
        <li>Des <strong>équipes sur le terrain</strong>, pas un standard téléphonique anonyme</li>
        <li>Du <strong>matériel professionnel</strong> : hydrocureur, caméra d'inspection, pompe de relevage</li>
        <li>Un <strong>prix annoncé avant l'intervention</strong>, sans mauvaise surprise sur la facture</li>
        <li>L'habitude des <strong>copropriétés, restaurants et collectivités</strong> du 94</li>
        <li>Une <strong>disponibilité 24h/7j</strong>, y compris les jours fériés</li>
      </ul>

      <p>
        Découvrez aussi nos prestations complémentaires :
        <a href="<?= BASE_URL ?>/debouchage">débouchage canalisation</a>,
        <a href="<?= BASE_URL ?>/curage">curage canalisation</a>,
        <a href="<?= BASE_URL ?>/inspection">inspection vidéo canalisation</a> et
        <a href="<?= BASE_URL ?>/pompage">pompage et vidange</a>.
      </p>

      <!-- ===================== H2 : FAQ ===================== -->
      <?php
      require_once APP_PATH . '/helpers/faq.php';
      sahp_render_faq([
        [
          'q' => 'Quand appeler une entreprise pour une canalisation bouchée dans le Val-de-Marne ?',
          'a' => '<p>Dès les premiers signes : écoulement lent, odeurs persistantes, gargouillis ou remontées d\'eau. Plus le bouchon est pris tôt, plus l\'intervention est simple. En cas de débordement ou de refoulement, il s\'agit d\'une <a href="' . BASE_URL . '/urgence">urgence</a> : mieux vaut nous appeler sans attendre pour limiter les dégâts.</p>',
        ],
        [
          'q' => 'SAHP intervient-il pour un WC bouché dans le 94 ?',
          'a' => '<p>Oui. Le débouchage de WC fait partie de nos interventions les plus courantes dans le Val-de-Marne. Dans la grande majorité des cas, nous débouchons la cuvette sans la déposer ni casser quoi que ce soit, à l\'aide d\'un furet adapté ou du jet haute pression selon la nature du bouchon.</p>',
        ],
        [
          'q' => 'Quelle est la différence entre débouchage, curage et hydrocurage ?',
          'a' => '<p>Le <a href="' . BASE_URL . '/debouchage">débouchage</a> règle un bouchon ponctuel à un endroit précis. Le <a href="' . BASE_URL . '/curage">curage</a> nettoie toute la longueur de la canalisation et décolle les dépôts sur les parois. L\'hydrocurage est la technique de curage par jet d\'eau haute pression : c\'est la solution quand les bouchons reviennent régulièrement.</p>',
        ],
        [
          'q' => 'Combien coûte un débouchage canalisation dans le Val-de-Marne ?',
          'a' => '<p>Le prix dépend de la nature du bouchon, de son emplacement, de la technique nécessaire et du caractère urgent de l\'intervention. Nous annonçons toujours le tarif avant d\'intervenir. Pour une estimation adaptée à votre situation, demandez un <a href="' . BASE_URL . '/devis">devis gratuit</a>.</p>',
        ],
        [
          'q' => 'Une inspection vidéo est-elle nécessaire pour une canalisation bouchée ?',
          'a' => '<p>Pas systématiquement. Pour un bouchon simple, le débouchage suffit. L\'<a href="' . BASE_URL . '/inspection">inspection vidéo</a> devient utile quand les bouchons récidivent ou qu\'on suspecte un défaut sur le réseau (casse, contre-pente, racine) : la caméra localise précisément le problème sans casser le sol.</p>',
        ],
      ], 'Questions fréquentes');
      ?>

      <!-- ===================== CTA FINAL ===================== -->
      <div class="vdm-cta-final">
        <h2>Demandez votre intervention dans le Val-de-Marne</h2>
        <p>
          Particulier, syndic ou professionnel : SAHP intervient rapidement dans tout le 94
          pour le débouchage et l'entretien de vos canalisations.
        </p>
        <div class="vdm-cta-group">
          <a class="vdm-btn vdm-btn-call" href="tel:+33176242884">Appeler SAHP</a>
          <a class="vdm-btn vdm-btn-primary" href="<?= BASE_URL ?>/contact">Demander une intervention</a>
          <a class="vdm-btn vdm-btn-ghost" href="<?= BASE_URL ?>/devis">Obtenir un devis</a>
        </div>
      </div>

    </div>
  </div>
</section>
