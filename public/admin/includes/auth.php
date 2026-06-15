<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

const ADMIN_SESSION_USER_ID_KEY = 'admin_user_id';
const ADMIN_SESSION_USERNAME_KEY = 'admin_username';

/**
 * Recupere un administrateur par son identifiant de connexion.
 */
function findAdminUserByUsername(string $username): ?array
{
    $username = strtolower(trim($username));
    if ($username === '') {
        return null;
    }

    $databaseConnection = getDatabaseConnection();
    $query = $databaseConnection->prepare(
        'SELECT id, username, password_hash FROM admin_users WHERE username = :username LIMIT 1'
    );
    $query->execute(['username' => $username]);
    $adminUser = $query->fetch();

    return $adminUser ?: null;
}

/**
 * Verifie un mot de passe admin.
 */
function verifyAdminPassword(string $password, string $passwordHash): bool
{
    $passwordHash = trim($passwordHash);

    return $passwordHash !== '' && password_verify($password, $passwordHash);
}

/**
 * Connecte un administrateur en session.
 */
function loginAdminUser(array $adminUser): void
{
    session_regenerate_id(true);
    $_SESSION[ADMIN_SESSION_USER_ID_KEY] = (int) $adminUser['id'];
    $_SESSION[ADMIN_SESSION_USERNAME_KEY] = $adminUser['username'];
}

/**
 * Indique si une session admin est active.
 */
function isAdminAuthenticated(): bool
{
    return isset($_SESSION[ADMIN_SESSION_USER_ID_KEY], $_SESSION[ADMIN_SESSION_USERNAME_KEY]);
}

/**
 * Protege une page admin en forçant la connexion.
 */
function requireAdminLogin(): void
{
    if (isAdminAuthenticated()) {
        return;
    }

    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}

/**
 * Deconnecte l'administrateur.
 */
function logoutAdminUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }

    session_destroy();
}

/**
 * Liste des pseudos (minuscules) autorisés à l’onglet HTML brut (.env ADMIN_ARTICLE_HTML_USERNAMES, séparés par virgules).
 *
 * @return list<string>
 */
function get_admin_article_html_editor_usernames(): array
{
    static $cache = null;
    if (is_array($cache)) {
        return $cache;
    }

    $rawFromEnv = $_ENV['ADMIN_ARTICLE_HTML_USERNAMES'] ?? getenv('ADMIN_ARTICLE_HTML_USERNAMES');
    $raw = is_string($rawFromEnv) ? $rawFromEnv : '';
    $parts = preg_split('/\s*,\s*/', trim($raw), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $resolved = [];
    foreach ($parts as $part) {
        $n = strtolower(trim($part));
        if ($n !== '') {
            $resolved[] = $n;
        }
    }
    $cache = array_values(array_unique($resolved));

    return $cache;
}

/**
 * Onglet « Avancé » (édition HTML directe) réservé aux comptes listés dans ADMIN_ARTICLE_HTML_USERNAMES.
 */
function adminUserCanEditRawArticleHtml(): bool
{
    if (! isAdminAuthenticated()) {
        return false;
    }

    $current = strtolower(trim((string) ($_SESSION[ADMIN_SESSION_USERNAME_KEY] ?? '')));
    if ($current === '') {
        return false;
    }

    $allowed = get_admin_article_html_editor_usernames();

    return in_array($current, $allowed, true);
}
