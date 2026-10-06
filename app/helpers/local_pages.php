<?php

declare(strict_types=1);

/**
 * Registre central des pages SEO locales (service + ville).
 *
 * Pour activer une nouvelle page :
 * 1. Ajouter la route dans public/index.php ($routes)
 * 2. Créer la vue app/Views/pages/{fichier}.php
 * 3. Ajouter le slug dans sahp_local_pages_live_routes()
 * 4. Ajouter l'entrée SEO dans app/helpers/seo.php si besoin
 *
 * Seules les pages « live » génèrent des liens (pas de lien cassé).
 *
 * @return list<array{route: string, label: string, service: string, zone: string}>
 */
function sahp_local_pages_registry(): array
{
    return [
        // Débouchage
        ['route' => 'debouchage-canalisation-valenton', 'label' => 'Débouchage canalisation à Valenton', 'service' => 'debouchage', 'zone' => 'val-de-marne'],
        ['route' => 'debouchage-canalisation-creteil', 'label' => 'Débouchage canalisation à Créteil', 'service' => 'debouchage', 'zone' => 'val-de-marne'],
        ['route' => 'debouchage-canalisation-ivry-sur-seine', 'label' => 'Débouchage canalisation à Ivry-sur-Seine', 'service' => 'debouchage', 'zone' => 'val-de-marne'],
        ['route' => 'debouchage-canalisation-orly', 'label' => 'Débouchage canalisation à Orly', 'service' => 'debouchage', 'zone' => 'val-de-marne'],
        // Curage
        ['route' => 'curage-canalisation-orly', 'label' => 'Curage canalisation à Orly', 'service' => 'curage', 'zone' => 'val-de-marne'],
        ['route' => 'curage-canalisation-bonneuil-sur-marne', 'label' => 'Curage canalisation à Bonneuil-sur-Marne', 'service' => 'curage', 'zone' => 'val-de-marne'],
        ['route' => 'curage-canalisation-champigny-sur-marne', 'label' => 'Curage canalisation à Champigny-sur-Marne', 'service' => 'curage', 'zone' => 'val-de-marne'],
        // Hydrocurage
        ['route' => 'hydrocurage-canalisation-villejuif', 'label' => 'Hydrocurage canalisation à Villejuif', 'service' => 'hydrocurage', 'zone' => 'val-de-marne'],
        ['route' => 'hydrocurage-canalisation-villeneuve-saint-georges', 'label' => 'Hydrocurage canalisation à Villeneuve-Saint-Georges', 'service' => 'hydrocurage', 'zone' => 'val-de-marne'],
        // Inspection vidéo
        ['route' => 'inspection-video-canalisation-creteil', 'label' => 'Inspection vidéo canalisation à Créteil', 'service' => 'inspection', 'zone' => 'val-de-marne'],
        ['route' => 'inspection-video-canalisation-vitry-sur-seine', 'label' => 'Inspection vidéo canalisation à Vitry-sur-Seine', 'service' => 'inspection', 'zone' => 'val-de-marne'],
        ['route' => 'inspection-video-canalisation-saint-maur-des-fosses', 'label' => 'Inspection vidéo canalisation à Saint-Maur-des-Fossés', 'service' => 'inspection', 'zone' => 'val-de-marne'],
        // Pompage / vidange
        ['route' => 'pompage-assainissement-choisy-le-roi', 'label' => 'Pompage assainissement à Choisy-le-Roi', 'service' => 'pompage', 'zone' => 'val-de-marne'],
        ['route' => 'pompage-fosse-septique-maisons-alfort', 'label' => 'Pompage fosse septique à Maisons-Alfort', 'service' => 'pompage', 'zone' => 'val-de-marne'],
        ['route' => 'pompage-assainissement-fontenay-sous-bois', 'label' => 'Pompage assainissement à Fontenay-sous-Bois', 'service' => 'pompage', 'zone' => 'val-de-marne'],
    ];
}

/**
 * Slugs de pages locales en ligne (alignés sur public/index.php).
 *
 * @return list<string>
 */
function sahp_local_pages_live_routes(): array
{
    return [
        'inspection-video-canalisation-creteil',
        'pompage-assainissement-choisy-le-roi',
    ];
}

/**
 * Pages locales publiées (registre filtré par routes live).
 *
 * @return list<array{route: string, label: string, service: string, zone: string, href: string}>
 */
function sahp_local_pages_resolved(): array
{
    $live = array_flip(sahp_local_pages_live_routes());
    $resolved = [];

    foreach (sahp_local_pages_registry() as $page) {
        if (!isset($live[$page['route']])) {
            continue;
        }
        $resolved[] = $page + ['href' => BASE_URL . '/' . $page['route']];
    }

    return $resolved;
}

/**
 * Filtre les pages locales par service ou zone.
 *
 * @param array{service?: string, zone?: string} $filters
 * @return list<array{route: string, label: string, service: string, zone: string, href: string}>
 */
function sahp_local_pages_filter(array $filters = []): array
{
    return array_values(array_filter(
        sahp_local_pages_resolved(),
        static function (array $page) use ($filters): bool {
            if (isset($filters['service']) && $page['service'] !== $filters['service']) {
                return false;
            }
            if (isset($filters['zone']) && $page['zone'] !== $filters['zone']) {
                return false;
            }

            return true;
        }
    ));
}

/**
 * Association article Paroles de Pro → page locale de conversion.
 *
 * @return array{route: string, label: string, cta: string}|null
 */
function sahp_blog_local_page_for_slug(string $slug): ?array
{
    $map = [
        'inspection-video-canalisation-creteil-quand-controler' => [
            'route' => 'inspection-video-canalisation-creteil',
            'label' => 'inspection vidéo canalisation à Créteil',
            'cta' => 'Besoin d\'une inspection vidéo canalisation à Créteil ?',
        ],
        'pompage-assainissement-choisy-le-roi-quand-intervenir' => [
            'route' => 'pompage-assainissement-choisy-le-roi',
            'label' => 'pompage assainissement à Choisy-le-Roi',
            'cta' => 'Besoin d\'un pompage assainissement à Choisy-le-Roi ?',
        ],
    ];

    $entry = $map[$slug] ?? null;
    if ($entry === null) {
        return null;
    }

    if (!in_array($entry['route'], sahp_local_pages_live_routes(), true)) {
        return null;
    }

    return $entry + ['href' => BASE_URL . '/' . $entry['route']];
}

/**
 * Rend une section de liens internes vers pages locales.
 * Ne rend rien si aucune page live ne correspond.
 *
 * @param list<array{route: string, label: string, service: string, zone: string, href: string}>|null $links
 */
function sahp_render_local_links_section(
    string $title,
    ?string $intro = null,
    ?array $links = null,
    string $modifier = ''
): void {
    $links = $links ?? sahp_local_pages_resolved();

    if ($links === []) {
        return;
    }

    $modifierClass = $modifier !== '' ? ' local-links-section--' . $modifier : '';

    require VIEWS_PATH . '/components/local-links-section.php';
}

/**
 * Section de maillage pour une page service principale.
 */
function sahp_render_service_local_links(string $service, string $title, ?string $intro = null): void
{
    sahp_render_local_links_section(
        $title,
        $intro,
        sahp_local_pages_filter(['service' => $service]),
        'service'
    );
}

/**
 * Section de maillage pour une page zone (ex. Val-de-Marne 94).
 */
function sahp_render_zone_local_links(string $zone, string $title, ?string $intro = null): void
{
    sahp_render_local_links_section(
        $title,
        $intro,
        sahp_local_pages_filter(['zone' => $zone]),
        'zone'
    );
}

/**
 * CTA article blog → page locale correspondante.
 */
function sahp_render_blog_local_page_cta(string $articleSlug): void
{
    $local = sahp_blog_local_page_for_slug($articleSlug);
    if ($local === null) {
        return;
    }

    require VIEWS_PATH . '/components/blog-local-page-cta.php';
}
