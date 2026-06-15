-- SAHP — 3 articles de démonstration (blog « Paroles de Pro »)
-- À exécuter dans phpMyAdmin après admin_v1.sql
-- Si un slug existe déjà : supprimer la ligne ou changer le slug avant INSERT.

INSERT INTO articles (
    title,
    slug,
    excerpt,
    content,
    cover_image,
    category,
    tags,
    is_published,
    published_at
) VALUES
(
    'Curage de canalisation : quand le faire et pourquoi',
    'curage-canalisation-quand-le-faire',
    'Un curage régulier évite les refoulements et prolonge la durée de vie de vos réseaux. Voici les signes qui doivent vous alerter.',
    '<h2>Pourquoi curer ses canalisations ?</h2>
<p>Le curage consiste à évacuer les dépôts (graisses, calcaire, boues) qui s''accumulent dans les tuyaux. Sans entretien, le diamètre utile diminue et les risques de bouchon augmentent.</p>
<h2>À quelle fréquence ?</h2>
<p>Pour une maison individuelle, un contrôle tous les 2 à 5 ans est souvent suffisant. Pour les professionnels (restauration, industrie), un planning annuel est recommandé.</p>
<ul>
<li>Eaux lentes à l''évier ou aux WC</li>
<li>Odeurs persistantes dans les sanitaires</li>
<li>Bruits de gargouillement dans les canalisations</li>
</ul>
<p>SAHP intervient en Île-de-France avec du matériel haute pression adapté à chaque réseau.</p>',
    NULL,
    'Conseils',
    '["curage", "entretien", "canalisation"]',
    1,
    '2026-05-10 09:00:00'
),
(
    'Débouchage : 5 signes avant un refoulement',
    'debouchage-signes-avant-refoulement',
    'Un bouchon ne se forme pas du jour au lendemain. Repérez ces signes pour intervenir avant l''urgence.',
    '<h2>Les signes les plus fréquents</h2>
<p>Avant un refoulement complet, votre installation envoie souvent des signaux faibles : écoulement ralenti, odeurs, ou remontées dans un autre point d''eau.</p>
<h2>Que faire en premier ?</h2>
<p>Évitez les produits chimiques agressifs qui peuvent abîmer les joints et les PVC. Si le problème persiste plus de 24 h, faites diagnostiquer le réseau.</p>
<ul>
<li>WC qui se vide mal ou à plusieurs tentatives</li>
<li>Douche ou lavabo qui se vide lentement</li>
<li>Eau qui remonte dans la baignoire quand vous tirez la chasse</li>
<li>Bruits d''aspiration dans les siphons</li>
<li>Odeurs d''égout dans la cuisine ou la salle de bain</li>
</ul>
<p>Nos équipes SAHP réalisent le débouchage mécanique ou hydrocurage selon la cause réelle du blocage.</p>',
    NULL,
    'Débouchage',
    '["debouchage", "urgence", "prevention"]',
    1,
    '2026-05-18 14:30:00'
),
(
    'Assainissement en urgence : les bons réflexes',
    'assainissement-urgence-bons-reflexes',
    'Refoulement, fosse pleine ou pompe en panne : les gestes utiles en attendant l''intervention SAHP 24h/7j.',
    '<h2>Sécuriser la situation</h2>
<p>En cas de refoulement, coupez l''arrivée d''eau si possible et n''utilisez plus les sanitaires concernés. Éloignez les objets de valeur du sol au sous-sol.</p>
<h2>Quand appeler un professionnel ?</h2>
<p>Dès que l''eau remonte dans plusieurs pièces ou que la fosse / station de relevage déborde, une intervention rapide limite les dégâts et les coûts de remise en état.</p>
<h2>Ce que SAHP peut faire sur place</h2>
<p>Pompage, débouchage, curage, inspection caméra : nous identifions la cause et rétablissons le fonctionnement avec un devis clair avant travaux lourds.</p>
<p>En Île-de-France, contactez SAHP pour une prise en charge d''urgence — disponible 24h/7j.</p>',
    NULL,
    'Urgence',
    '["urgence", "pompage", "refoulement"]',
    1,
    '2026-05-26 08:00:00'
);
