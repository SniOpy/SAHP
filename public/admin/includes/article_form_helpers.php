<?php

declare(strict_types=1);

/**
 * Genere un slug URL a partir du titre (lettres, chiffres, tirets).
 */
function generateArticleSlugFromTitle(string $title): string
{
    $normalizedTitle = strtolower(trim($title));
    $transliteratedTitle = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $normalizedTitle);
    if ($transliteratedTitle !== false && $transliteratedTitle !== '') {
        $normalizedTitle = strtolower($transliteratedTitle);
    }

    $slugWithHyphens = preg_replace('/[^a-z0-9]+/', '-', $normalizedTitle) ?? '';
    $slugWithHyphens = trim($slugWithHyphens, '-');

    return $slugWithHyphens !== '' ? $slugWithHyphens : 'article';
}

/**
 * Nettoie un slug saisi manuellement.
 */
function normalizeArticleSlug(string $slug): string
{
    $slug = strtolower(trim($slug));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug) ?? '';
    $slug = preg_replace('/-+/', '-', $slug) ?? '';

    return trim($slug, '-');
}

/**
 * Verifie le format du slug (non vide, caracteres autorises).
 */
function isArticleSlugValid(string $slug): bool
{
    if ($slug === '' || strlen($slug) > 255) {
        return false;
    }

    return (bool) preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug);
}

/**
 * Nettoie le HTML du contenu : balises utiles pour un futur editeur riche.
 */
function sanitizeArticleContentHtml(string $html): string
{
    $allowedTags = '<p><br><strong><em><b><i><u><h2><h3><h4><ul><ol><li><a><img><blockquote><div><span><hr>';

    return strip_tags($html, $allowedTags);
}

/**
 * Texte brut pour l'extrait (pas de HTML).
 */
function sanitizeArticleExcerpt(string $excerpt): string
{
    return trim(strip_tags($excerpt));
}

/**
 * Parse les tags separes par des virgules en tableau de chaines.
 *
 * @return array<int, string>
 */
function parseArticleTagsFromCommaString(string $tagsInput): array
{
    $parts = preg_split('/\s*,\s*/', trim($tagsInput), -1, PREG_SPLIT_NO_EMPTY) ?: [];

    $cleanTags = [];
    foreach ($parts as $part) {
        $tag = trim($part);
        if ($tag !== '' && strlen($tag) <= 80) {
            $cleanTags[] = $tag;
        }
    }

    return array_values(array_unique($cleanTags));
}

/**
 * Encode les tags en JSON pour la colonne JSON MySQL.
 */
function encodeArticleTagsAsJson(array $tagsList): ?string
{
    if ($tagsList === []) {
        return null;
    }

    return json_encode(array_values($tagsList), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
}
