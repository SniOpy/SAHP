<?php

declare(strict_types=1);

/**
 * SEO centralisé pour SAHP.
 *
 * - Titres optimisés (55-61 caractères) par page
 * - Meta descriptions par page
 * - URL canonique absolue
 * - Balises Open Graph + Twitter Card
 *
 * Le tableau de pages est indexé par segment d'URL (aligné sur $routes de
 * public/index.php). La clé '' correspond à la page d'accueil.
 */

require_once __DIR__ . '/sitemap_helper.php';

/**
 * Image de partage par défaut (chemin relatif, sans BASE_URL).
 */
function sahp_seo_default_image(): string
{
    return 'assets/img/hero.jpg';
}

/**
 * Carte SEO par page : title (55-61 car.), description (~150-160 car.), image OG.
 *
 * @return array<string, array{title: string, description: string, image?: string}>
 */
function sahp_seo_map(): array
{
    return [
        '' => [
            'title' => 'SAHP | Assainissement & débouchage 24h/7j Île-de-France',
            'description' => "SAHP, votre entreprise d'assainissement en Île-de-France : débouchage, curage haute pression, pompage, vidange et inspection vidéo. Intervention rapide 24h/7j.",
            'image' => 'assets/img/hero.jpg',
        ],
        'a-propos' => [
            'title' => 'À propos de SAHP, expert assainissement en Île-de-France',
            'description' => "Découvrez SAHP, entreprise d'assainissement en Île-de-France : nos équipes, nos valeurs et notre savoir-faire au service des particuliers et des professionnels.",
            'image' => 'assets/img/equipe.jpg',
        ],
        'mentions-legales' => [
            'title' => 'Mentions légales | SAHP, assainissement en Île-de-France',
            'description' => "Mentions légales du site SAHP Assainissement : éditeur, hébergeur et informations légales de notre entreprise d'assainissement en Île-de-France.",
        ],
        'conditions-generales-prestations-services' => [
            'title' => 'Conditions générales de prestations | SAHP Assainissement',
            'description' => "Conditions générales de prestations SAHP : modalités d'intervention, devis, paiement et garanties pour nos travaux d'assainissement en Île-de-France.",
        ],
        'politique-confidentialite' => [
            'title' => 'Politique de confidentialité du site SAHP Assainissement',
            'description' => "Politique de confidentialité de SAHP : comment nous collectons, utilisons et protégeons vos données personnelles lors de vos demandes de devis et de contact.",
        ],
        'gestion-cookies' => [
            'title' => 'Gestion des cookies | SAHP Assainissement Île-de-France',
            'description' => "Gérez vos préférences de cookies sur le site SAHP Assainissement : cookies essentiels, mesure d'audience et consentement, en toute transparence.",
        ],
        'plan-site' => [
            'title' => 'Plan du site complet | SAHP Assainissement Île-de-France',
            'description' => "Plan du site SAHP Assainissement : retrouvez rapidement toutes nos pages services, conseils et informations pratiques pour l'assainissement en Île-de-France.",
        ],
        'curage' => [
            'title' => 'Curage de canalisation haute pression | SAHP Île-de-France',
            'description' => "Curage haute pression de vos canalisations par SAHP en Île-de-France : élimination des dépôts, prévention des bouchons et entretien durable de vos réseaux.",
            'image' => 'assets/img/curage.jpg',
        ],
        'pompage' => [
            'title' => 'Pompage et vidange de fosse septique | SAHP Île-de-France',
            'description' => "Pompage et vidange de fosse septique, bac à graisse et eaux usées par SAHP en Île-de-France. Camion hydrocureur, intervention rapide pour particuliers et pros.",
            'image' => 'assets/img/pompage.jpg',
        ],
        'inspection' => [
            'title' => 'Inspection vidéo de canalisation par caméra en IDF | SAHP',
            'description' => "Inspection vidéo de canalisation par caméra avec SAHP en Île-de-France : diagnostic précis, localisation des défauts et rapport clair avant travaux.",
            'image' => 'assets/img/inspection-video.jpg',
        ],
        'debouchage' => [
            'title' => 'Débouchage de canalisation en urgence | SAHP Île-de-France',
            'description' => "Débouchage de canalisation en urgence par SAHP en Île-de-France : WC, évier, douche, colonne d'immeuble. Intervention rapide 24h/7j, particuliers et pros.",
            'image' => 'assets/img/debouchage.jpg',
        ],
        'debouchage-canalisation-val-de-marne-94' => [
            'title' => 'Débouchage canalisation Val-de-Marne 94 | Intervention SAHP',
            'description' => "Canalisation bouchée dans le Val-de-Marne ? SAHP intervient pour le débouchage canalisation, WC, évier, égout, curage et inspection vidéo dans le 94.",
            'image' => 'assets/img/debouchage.jpg',
        ],
        'inspection-video-canalisation-creteil' => [
            'title' => 'Inspection vidéo canalisation Créteil | Diagnostic SAHP',
            'description' => "Besoin d'une inspection vidéo canalisation à Créteil ? SAHP contrôle vos canalisations par caméra pour identifier bouchon, casse, racines ou défaut de réseau.",
            'image' => 'assets/img/inspection-video.jpg',
        ],
        'pompage-assainissement-choisy-le-roi' => [
            'title' => 'Pompage assainissement Choisy-le-Roi | SAHP',
            'description' => "Besoin d'un pompage assainissement à Choisy-le-Roi ? SAHP intervient pour eaux usées, fosse, bac à graisse, regard saturé, vidange et urgence assainissement.",
            'image' => 'assets/img/pompage.jpg',
        ],
        'maintenance-pro' => [
            'title' => 'Maintenance assainissement pour pros | SAHP Île-de-France',
            'description' => "Contrats de maintenance assainissement pour professionnels et copropriétés en Île-de-France : entretien préventif, curage planifié et suivi des réseaux.",
        ],
        'urgence' => [
            'title' => 'Urgence débouchage & assainissement 24h/7j en IDF | SAHP',
            'description' => "Urgence assainissement 24h/7j avec SAHP en Île-de-France : refoulement, débouchage, pompage et fosse pleine. Intervention rapide pour limiter les dégâts.",
            'image' => 'assets/img/intervention.jpg',
        ],
        'paroles-de-pro' => [
            'title' => 'Paroles de Pro : conseils en assainissement par SAHP IDF',
            'description' => "Paroles de Pro : les conseils d'experts SAHP sur le curage, le débouchage, l'entretien et les urgences d'assainissement en Île-de-France.",
        ],
        'contact' => [
            'title' => 'Contact SAHP | Assainissement & urgence en Île-de-France',
            'description' => "Contactez SAHP, votre entreprise d'assainissement en Île-de-France. Devis gratuit, conseils et intervention d'urgence 24h/7j pour particuliers et pros.",
        ],
        'devis' => [
            'title' => 'Devis gratuit assainissement & débouchage en Île-de-France',
            'description' => "Demandez votre devis gratuit d'assainissement en Île-de-France avec SAHP : débouchage, curage, pompage et inspection. Réponse rapide, sans engagement.",
        ],
        'tarifs' => [
            'title' => 'Tarifs assainissement : curage, débouchage & pompage SAHP',
            'description' => "Découvrez les tarifs SAHP pour le débouchage, le curage, le pompage et l'inspection de canalisation en Île-de-France. Prix clairs et devis gratuit sur demande.",
        ],
    ];
}

/**
 * Tronque une meta description à ~150 caractères (coupure au dernier mot complet).
 */
function sahp_truncate_meta_description(string $text, int $max = 150): string
{
    $text = trim(preg_replace('/\s+/', ' ', $text) ?? $text);
    if (mb_strlen($text) <= $max) {
        return $text;
    }

    $cut = mb_substr($text, 0, $max);
    $lastSpace = mb_strrpos($cut, ' ');
    if ($lastSpace !== false && $lastSpace > (int) ($max * 0.6)) {
        $cut = mb_substr($cut, 0, $lastSpace);
    }

    return rtrim($cut, '.,;:!?') . '…';
}

/**
 * Métadonnées SEO d'une page statique (par segment d'URL), ou null si absente.
 * Les descriptions sont tronquées à 150 caractères.
 *
 * @return array{title: string, description: string, image?: string}|null
 */
function sahp_seo_for_path(string $path): ?array
{
    $map = sahp_seo_map();
    $entry = $map[$path] ?? null;
    if ($entry === null) {
        return null;
    }

    $entry['description'] = sahp_truncate_meta_description($entry['description']);

    return $entry;
}

/**
 * URL canonique absolue pour un chemin (« devis », « », « paroles-de-pro/slug »).
 * Retourne null si aucun chemin canonique ne doit être émis.
 */
function sahp_seo_canonical_url(?string $path): ?string
{
    if ($path === null) {
        return null;
    }

    $origin = sitemap_resolve_site_origin();

    return sitemap_full_url($origin, $path === '' ? '/' : $path);
}

/**
 * Transforme un chemin d'asset relatif (« assets/img/x.jpg ») en URL absolue.
 */
function sahp_seo_absolute_asset(string $relative): string
{
    $origin = rtrim(sitemap_resolve_site_origin(), '/');

    return $origin . '/' . ltrim($relative, '/');
}

/**
 * Rend les balises canonical + Open Graph + Twitter Card.
 *
 * @param array{
 *     title: string,
 *     description: string,
 *     canonical?: string|null,
 *     image?: string,
 *     type?: string
 * } $ctx
 */
function sahp_render_seo_tags(array $ctx): void
{
    $title = (string) ($ctx['title'] ?? 'SAHP Assainissement');
    $description = (string) ($ctx['description'] ?? '');
    $canonical = $ctx['canonical'] ?? null;
    $type = (string) ($ctx['type'] ?? 'website');
    $imageRelative = (string) ($ctx['image'] ?? sahp_seo_default_image());
    $imageUrl = sahp_seo_absolute_asset($imageRelative);

    $esc = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

    if (is_string($canonical) && $canonical !== '') {
        echo '  <link rel="canonical" href="' . $esc($canonical) . '">' . "\n";
    }

    // Open Graph
    echo '  <meta property="og:type" content="' . $esc($type) . '">' . "\n";
    echo '  <meta property="og:site_name" content="SAHP Assainissement">' . "\n";
    echo '  <meta property="og:locale" content="fr_FR">' . "\n";
    echo '  <meta property="og:title" content="' . $esc($title) . '">' . "\n";
    if ($description !== '') {
        echo '  <meta property="og:description" content="' . $esc($description) . '">' . "\n";
    }
    if (is_string($canonical) && $canonical !== '') {
        echo '  <meta property="og:url" content="' . $esc($canonical) . '">' . "\n";
    }
    echo '  <meta property="og:image" content="' . $esc($imageUrl) . '">' . "\n";

    // Twitter Card
    echo '  <meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '  <meta name="twitter:title" content="' . $esc($title) . '">' . "\n";
    if ($description !== '') {
        echo '  <meta name="twitter:description" content="' . $esc($description) . '">' . "\n";
    }
    echo '  <meta name="twitter:image" content="' . $esc($imageUrl) . '">' . "\n";
}

/**
 * JSON-LD LocalBusiness + Person (directeur) pour la page d'accueil (EEAT / GEO).
 */
function sahp_render_local_business_schema(): void
{
    $origin = sitemap_resolve_site_origin();
    $logoUrl = sahp_seo_absolute_asset('assets/img/sahp.png');

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        '@id' => $origin . '/#organization',
        'name' => 'SAHP Assainissement',
        'alternateName' => 'Société d\'Assainissement et d\'Hygiène Parisienne',
        'url' => $origin . '/',
        'logo' => $logoUrl,
        'image' => sahp_seo_absolute_asset(sahp_seo_default_image()),
        'telephone' => '+33176242884',
        'email' => 'contact@sahp-idf.fr',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '4 rue Aminata Traoré',
            'addressLocality' => 'Valenton',
            'postalCode' => '94460',
            'addressCountry' => 'FR',
        ],
        'areaServed' => [
            '@type' => 'AdministrativeArea',
            'name' => 'Île-de-France',
        ],
        'description' => 'Entreprise d\'assainissement : débouchage, curage haute pression, pompage, inspection vidéo. Intervention 24h/7j.',
        'foundingDate' => '2015',
        'priceRange' => '€€',
        'openingHoursSpecification' => [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'opens' => '00:00',
            'closes' => '23:59',
        ],
        'founder' => [
            '@type' => 'Person',
            'name' => 'Nabyl Mekaouche',
            'jobTitle' => 'Directeur de publication',
        ],
    ];

    echo "\n" . '<script type="application/ld+json">'
        . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        . '</script>' . "\n";
}
