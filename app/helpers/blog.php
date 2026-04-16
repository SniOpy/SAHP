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
 * Image de carte (liste) : si pas de visuel en base, même repli que l’ancien contenu (visuel cohérent).
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
            'SELECT id, title, slug, excerpt, content, cover_image, category, tags, is_published, published_at, created_at, updated_at
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
        'content' => (string) ($row['content'] ?? ''),
        'cover_image' => $row['cover_image'] !== null ? (string) $row['cover_image'] : '',
        'category' => $row['category'] !== null ? (string) $row['category'] : '',
        'tags' => $tagsList,
        'published_at' => blog_format_published_date_for_display(
            is_string($publishedAt) ? $publishedAt : null,
            is_string($createdAt) ? $createdAt : null
        ),
        'published_at_raw' => is_string($publishedAt) ? $publishedAt : null,
    ];
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
            'SELECT id, title, slug, excerpt, content, cover_image, category, tags, is_published, published_at, created_at, updated_at
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
