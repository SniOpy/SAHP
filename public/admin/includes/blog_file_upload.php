<?php

declare(strict_types=1);

const BLOG_IMAGE_UPLOAD_MAX_BYTES = 2097152; // 2 Mo

const BLOG_IMAGE_ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

const BLOG_IMAGE_ALLOWED_MIME_TYPES = [
    'image/jpeg',
    'image/png',
    'image/webp',
];

/**
 * Chemin absolu du dossier public (ex: .../sahp/public).
 */
function getPublicDirectoryPath(): string
{
    return dirname(__DIR__, 2);
}

/**
 * Chemin absolu du dossier d'upload des images blog.
 */
function getBlogUploadDirectoryPath(): string
{
    return getPublicDirectoryPath() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'blog';
}

/**
 * URL publique d'une image blog (commence par BASE_URL).
 */
function buildBlogImagePublicUrl(string $fileName): string
{
    return BASE_URL . '/uploads/blog/' . $fileName;
}

/**
 * Verifie qu'une extension de fichier est autorisee pour le blog.
 */
function isBlogImageExtensionAllowed(string $extension): bool
{
    $normalizedExtension = strtolower($extension);

    return in_array($normalizedExtension, BLOG_IMAGE_ALLOWED_EXTENSIONS, true);
}

/**
 * Sauvegarde un fichier upload (image blog) et retourne le nom de fichier genere.
 *
 * @param array<string, mixed> $uploadedFile $_FILES['...']
 * @throws RuntimeException en cas d'erreur de validation ou d'ecriture disque
 */
function saveBlogImageFromUploadedFile(array $uploadedFile): string
{
    if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Fichier image manquant ou erreur de transfert.');
    }

    if (($uploadedFile['size'] ?? 0) > BLOG_IMAGE_UPLOAD_MAX_BYTES) {
        throw new RuntimeException('Image trop volumineuse (maximum 2 Mo).');
    }

    $temporaryPath = (string) ($uploadedFile['tmp_name'] ?? '');
    if ($temporaryPath === '' || !is_uploaded_file($temporaryPath)) {
        throw new RuntimeException('Fichier temporaire invalide.');
    }

    $originalName = (string) ($uploadedFile['name'] ?? '');
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!isBlogImageExtensionAllowed($extension)) {
        throw new RuntimeException('Format non autorise (jpg, png, webp uniquement).');
    }

    $mimeType = detectUploadedImageMimeType($temporaryPath);
    if (!in_array($mimeType, BLOG_IMAGE_ALLOWED_MIME_TYPES, true)) {
        throw new RuntimeException('Type MIME image non autorise.');
    }

    $uploadDirectory = getBlogUploadDirectoryPath();
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
        throw new RuntimeException('Impossible de creer le dossier d\'upload.');
    }

    $safeExtension = $extension === 'jpeg' ? 'jpg' : $extension;
    $newFileName = 'blog-' . bin2hex(random_bytes(16)) . '.' . $safeExtension;
    $destinationPath = $uploadDirectory . DIRECTORY_SEPARATOR . $newFileName;

    if (!move_uploaded_file($temporaryPath, $destinationPath)) {
        throw new RuntimeException('Impossible d\'enregistrer l\'image sur le serveur.');
    }

    return $newFileName;
}

/**
 * Detecte le type MIME reel du fichier (plus fiable que l'extension).
 */
function detectUploadedImageMimeType(string $filePath): string
{
    if (function_exists('finfo_open')) {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($fileInfo !== false) {
            $detectedMime = finfo_file($fileInfo, $filePath) ?: '';
            finfo_close($fileInfo);

            return $detectedMime;
        }
    }

    $imageSize = @getimagesize($filePath);

    return is_array($imageSize) && isset($imageSize['mime']) ? (string) $imageSize['mime'] : '';
}
