<?php

declare(strict_types=1);

/**
 * Espace médias SAHP — réglages
 * ------------------------------------------------------------
 * Textes, catégories et limites de la page /espace-medias.
 * La connexion à l'administration est celle du panneau admin (comptes admin_users).
 */
return [
    // --- Textes de la page publique ---
    'company'       => 'SAHP Assainissement',
    'page_title'    => 'Espace médias',
    'intro'         => 'Logos, cartes de visite, QR codes, plaquettes et photos : retrouvez ici les supports SAHP, prêts à télécharger.',
    'contact_phone' => '01 76 24 28 84',
    'contact_email' => 'contact@sahp-idf.fr',

    // --- Catégories ---
    // clé technique => libellé affiché. L'ordre ici = l'ordre sur la page.
    // Vous pouvez renommer un libellé librement, mais ne changez pas une clé
    // déjà utilisée (les fichiers rangés dedans passeraient en « Autres »).
    'categories' => [
        'logos'            => 'Logos',
        'cartes-de-visite' => 'Cartes de visite',
        'qr-codes'         => 'QR codes',
        'plaquettes'       => 'Plaquettes & brochures',
        'photos'           => 'Photos',
        'documents'        => 'Autres documents',
    ],

    // --- Limites ---
    'max_upload_mb' => 64,       // taille max d'un fichier (le serveur peut imposer moins, voir public/.user.ini)

    // true = la page publique demande à Google de ne pas l'indexer (et la retire du sitemap)
    'noindex' => false,
];
