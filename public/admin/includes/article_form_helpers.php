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
    $allowedTags = '<p><br><strong><em><b><i><u><h2><h3><h4><ul><ol><li><a><img><blockquote><div><span><hr><table><thead><tbody><tr><th><td><caption>';

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
 * Meta title SEO (texte brut, max 255).
 */
function sanitizeArticleMetaTitle(string $metaTitle): string
{
    $metaTitle = trim(strip_tags($metaTitle));

    return mb_strlen($metaTitle) > 255 ? mb_substr($metaTitle, 0, 255) : $metaTitle;
}

/**
 * Meta description SEO (texte brut, max 320).
 */
function sanitizeArticleMetaDescription(string $metaDescription): string
{
    $metaDescription = trim(strip_tags($metaDescription));
    $metaDescription = preg_replace('/\s+/u', ' ', $metaDescription) ?? $metaDescription;

    return mb_strlen($metaDescription) > 320 ? mb_substr($metaDescription, 0, 320) : $metaDescription;
}

/**
 * Valide les champs SEO optionnels.
 *
 * @param array<int, string> $validationErrors
 * @param array<string, string> $formFieldErrors
 */
function validateArticleSeoFields(
    string $fieldMetaTitle,
    string $fieldMetaDescription,
    array &$validationErrors,
    array &$formFieldErrors
): void {
    if (strlen($fieldMetaTitle) > 255) {
        $validationErrors[] = 'Le meta title est trop long (255 caractères maximum).';
        $formFieldErrors['meta_title'] = 'Raccourcissez le meta title.';
    }

    if (strlen($fieldMetaDescription) > 320) {
        $validationErrors[] = 'La meta description est trop longue (320 caractères maximum).';
        $formFieldErrors['meta_description'] = 'Raccourcissez la meta description (idéal : 150–160 caractères).';
    }
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

/** @param array<string, string> $formFieldErrors */
function admin_article_field_group_class(?array $formFieldErrors, string $fieldKey, string $baseClass = 'admin-field-group'): string
{
    if (! empty($formFieldErrors[$fieldKey])) {
        return $baseClass . ' admin-field-group--error';
    }

    return $baseClass;
}

/** @param array<string, string> $formFieldErrors */
function admin_article_field_error_notice(?array $formFieldErrors, string $fieldKey): string
{
    if (empty($formFieldErrors[$fieldKey])) {
        return '';
    }

    return '<p class="admin-field-invalid-msg" role="alert">' . htmlspecialchars($formFieldErrors[$fieldKey], ENT_QUOTES, 'UTF-8') . '</p>';
}

/**
 * Message utilisateur pour une erreur PDO à l'enregistrement d'un article.
 */
function admin_article_format_save_database_error(PDOException $pdoException): string
{
    $sqlErrorCode = (int) ($pdoException->errorInfo[1] ?? 0);
    $message = $pdoException->getMessage();

    if ($sqlErrorCode === 1062 || str_contains($message, 'Duplicate')) {
        return 'duplicate_slug';
    }

    if ($sqlErrorCode === 1054 && str_contains($message, 'meta_')) {
        return 'Colonnes SEO manquantes en base (meta_title, meta_description). Exécutez le fichier data/sql/migration_articles_meta.sql dans phpMyAdmin.';
    }

    error_log('[SAHP article save] ' . $message);

    return 'Erreur base de données lors de l\'enregistrement.';
}
