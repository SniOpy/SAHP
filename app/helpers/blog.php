<?php

declare(strict_types=1);

require_once __DIR__ . '/database.php';

function blog_escape(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * URL complète pour afficher l'image de couverture (ancien fichier dans /assets/img/blog/, URL absolue, ou chemin /sahp/public/...).
 */
function blog_resolve_cover_image_url(?string $coverImage): string
{
    $coverImage = trim((string) $coverImage);
    if ($coverImage === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $coverImage) === 1) {
        return $coverImage;
    }

    if (str_starts_with($coverImage, '/')) {
        return $coverImage;
    }

    return BASE_URL . '/assets/img/blog/' . $coverImage;
}

/**
 * Image de carte (liste) : si pas de visuel en base, même repli que l'ancien contenu (visuel cohérent).
 */
function blog_resolve_cover_image_url_for_card(?string $coverImage): string
{
    $url = blog_resolve_cover_image_url($coverImage);

    return $url !== '' ? $url : BASE_URL . '/assets/img/mascotte-blog.png';
}

/**
 * Formate une date MySQL pour affichage court (liste blog).
 */
function blog_format_published_date_for_display(?string $publishedAt, ?string $createdAt): string
{
    $raw = $publishedAt ?? $createdAt;
    if ($raw === null || $raw === '') {
        return '';
    }

    $timestamp = strtotime($raw);

    return $timestamp !== false ? date('d/m/Y', $timestamp) : '';
}

/**
 * Articles publiés, du plus récent au plus ancien.
 */
function blog_load_posts(): array
{
    try {
        $pdo = getAppDatabaseConnection();
        $statement = $pdo->query(
            'SELECT id, title, slug, excerpt, meta_title, meta_description, content, cover_image, category, tags, is_published, published_at, created_at, updated_at
             FROM articles
             WHERE is_published = 1
             ORDER BY COALESCE(published_at, created_at) DESC'
        );
        $rows = $statement->fetchAll();
    } catch (Throwable $exception) {
        return [];
    }

    return array_map('blog_map_article_row_for_views', $rows);
}

/**
 * @param array<string, mixed> $row
 * @return array<string, mixed>
 */
function blog_map_article_row_for_views(array $row): array
{
    $tagsRaw = $row['tags'] ?? null;
    $tagsList = [];
    if (is_string($tagsRaw) && $tagsRaw !== '') {
        $decoded = json_decode($tagsRaw, true);
        $tagsList = is_array($decoded) ? $decoded : [];
    }

    $publishedAt = $row['published_at'] ?? null;
    $createdAt = $row['created_at'] ?? null;

    return [
        'id' => (int) ($row['id'] ?? 0),
        'title' => (string) ($row['title'] ?? ''),
        'slug' => (string) ($row['slug'] ?? ''),
        'excerpt' => (string) ($row['excerpt'] ?? ''),
        'meta_title' => (string) ($row['meta_title'] ?? ''),
        'meta_description' => (string) ($row['meta_description'] ?? ''),
        'content' => (string) ($row['content'] ?? ''),
        'cover_image' => $row['cover_image'] !== null ? (string) $row['cover_image'] : '',
        'category' => $row['category'] !== null ? (string) $row['category'] : '',
        'tags' => $tagsList,
        'is_published' => (int) ($row['is_published'] ?? 0),
        'published_at' => blog_format_published_date_for_display(
            is_string($publishedAt) ? $publishedAt : null,
            is_string($createdAt) ? $createdAt : null
        ),
        'published_at_raw' => is_string($publishedAt) ? $publishedAt : null,
    ];
}

/**
 * Charge un article par son identifiant, SANS filtre de publication.
 * Réservé à un usage admin protégé (prévisualisation des brouillons).
 */
function blog_find_post_by_id(int $id): ?array
{
    if ($id < 1) {
        return null;
    }

    try {
        $pdo = getAppDatabaseConnection();
        $statement = $pdo->prepare(
            'SELECT id, title, slug, excerpt, meta_title, meta_description, content, cover_image, category, tags, is_published, published_at, created_at, updated_at
             FROM articles
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();
    } catch (Throwable $exception) {
        return null;
    }

    if ($row === false) {
        return null;
    }

    return blog_map_article_row_for_views($row);
}

function blog_find_post_by_slug(string $slug): ?array
{
    $slug = trim($slug);
    if ($slug === '') {
        return null;
    }

    try {
        $pdo = getAppDatabaseConnection();
        $statement = $pdo->prepare(
            'SELECT id, title, slug, excerpt, meta_title, meta_description, content, cover_image, category, tags, is_published, published_at, created_at, updated_at
             FROM articles
             WHERE slug = :slug AND is_published = 1
             LIMIT 1'
        );
        $statement->execute(['slug' => $slug]);
        $row = $statement->fetch();
    } catch (Throwable $exception) {
        return null;
    }

    if ($row === false) {
        return null;
    }

    return blog_map_article_row_for_views($row);
}

/**
 * Titre de page (&lt;title&gt;) pour un article publié.
 *
 * @param array<string, mixed> $post
 */
function blog_resolve_page_title(array $post): string
{
    $metaTitle = trim((string) ($post['meta_title'] ?? ''));
    if ($metaTitle !== '') {
        return $metaTitle;
    }

    $title = trim((string) ($post['title'] ?? ''));
    if ($title === '') {
        return 'Paroles de Pros | SAHP Assainissement';
    }

    return $title . ' | SAHP Assainissement';
}

/**
 * Meta description pour un article publié.
 *
 * @param array<string, mixed> $post
 */
function blog_resolve_meta_description(array $post): string
{
    require_once __DIR__ . '/seo.php';

    $metaDescription = trim((string) ($post['meta_description'] ?? ''));
    if ($metaDescription !== '') {
        return sahp_truncate_meta_description($metaDescription);
    }

    $excerpt = trim(strip_tags((string) ($post['excerpt'] ?? '')));
    if ($excerpt !== '') {
        return sahp_truncate_meta_description($excerpt);
    }

    return sahp_truncate_meta_description(
        "Conseils d'assainissement et d'entretien des réseaux par SAHP, expert en Île-de-France."
    );
}

/**
 * Extrait les paires question/réponse d'un bloc FAQ dans le HTML article
 * (H2 « Questions fréquentes » suivi de H3 + paragraphes) pour le JSON-LD.
 *
 * @return list<array{q: string, a: string}>
 */
function blog_extract_faq_from_content(string $html): array
{
    if (trim($html) === '') {
        return [];
    }

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $wrapped = '<?xml encoding="UTF-8"><div id="faq-root">' . $html . '</div>';
    if (! $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
        libxml_clear_errors();

        return [];
    }
    libxml_clear_errors();

    $root = $dom->getElementById('faq-root');
    if ($root === null) {
        return [];
    }

    $faqs = [];
    $inFaq = false;
    $currentQuestion = null;

    foreach ($root->childNodes as $node) {
        if (! $node instanceof DOMElement) {
            continue;
        }

        $tag = strtolower($node->tagName);

        if ($tag === 'h2') {
            $heading = trim($node->textContent ?? '');
            $inFaq = stripos($heading, 'questions fréquentes') !== false
                || stripos($heading, 'questions frequentes') !== false;
            $currentQuestion = null;
            continue;
        }

        if (! $inFaq) {
            continue;
        }

        if ($tag === 'h3') {
            $currentQuestion = trim($node->textContent ?? '');
            continue;
        }

        if ($tag === 'p' && $currentQuestion !== null && $currentQuestion !== '') {
            $answer = trim($node->textContent ?? '');
            if ($answer !== '') {
                $faqs[] = ['q' => $currentQuestion, 'a' => $answer];
                $currentQuestion = null;
            }
        }
    }

    return $faqs;
}

/**
 * Émet le JSON-LD FAQPage si le contenu article contient une section FAQ.
 */
function blog_render_faq_schema_from_content(string $html): void
{
    $faqs = blog_extract_faq_from_content($html);
    if ($faqs === []) {
        return;
    }

    $entities = [];
    foreach ($faqs as $faq) {
        $entities[] = [
            '@type' => 'Question',
            'name' => $faq['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['a'],
            ],
        ];
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $entities,
    ];

    echo "\n" . '<script type="application/ld+json">'
        . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        . '</script>' . "\n";
}

/**
 * Prépare le HTML article pour affichage : chemins relatifs du site → BASE_URL.
 */
function blog_prepare_content_html_for_output(string $html): string
{
    $content = $html;
    $content = str_replace('src="/assets/', 'src="' . BASE_URL . '/assets/', $content);
    $content = str_replace('src="/uploads/', 'src="' . BASE_URL . '/uploads/', $content);
    $content = str_replace('href="/', 'href="' . BASE_URL . '/', $content);

    return $content;
}
