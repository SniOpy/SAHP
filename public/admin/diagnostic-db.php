<?php

declare(strict_types=1);

/**
 * Diagnostic BDD admin — SUPPRIMER CE FICHIER après usage.
 * URL : /admin/diagnostic-db.php?key=sahp-diag-2026
 */

$expectedKey = 'sahp-diag-2026';
if (($_GET['key'] ?? '') !== $expectedKey) {
    http_response_code(404);
    exit('Not found');
}

header('Content-Type: text/plain; charset=UTF-8');

require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/includes/db.php';

echo "=== Diagnostic SAHP (admin BDD) ===\n\n";

echo "Fichier de configuration :\n";
echo '  Charge : ' . (defined('SAHP_DOTENV_LOADED_PATH') ? SAHP_DOTENV_LOADED_PATH : '(aucun)') . "\n";

$projectRoot = dirname(__DIR__, 2);
$envCandidates = [
    $projectRoot . '/.env',
    dirname($projectRoot) . '/.env',
    dirname(__DIR__, 2) . '/app/config/env.local.php',
];
$docRoot = (string) ($_SERVER['DOCUMENT_ROOT'] ?? '');
if ($docRoot !== '') {
    $envCandidates[] = rtrim($docRoot, '/\\') . '/../.env';
}
echo "Chemins testes :\n";
foreach ($envCandidates as $path) {
    echo '  - ' . $path . ' : ' . (is_readable($path) ? 'OK (lisible)' : 'absent') . "\n";
}

echo "\nVariables chargees (sans mot de passe) :\n";
echo '  DB_HOST = ' . ($_ENV['DB_HOST'] ?? '(vide)') . "\n";
echo '  DB_NAME = ' . ($_ENV['DB_NAME'] ?? '(vide)') . "\n";
echo '  DB_USER = ' . ($_ENV['DB_USER'] ?? '(vide)') . "\n";
echo '  DB_PASS = ' . (empty($_ENV['DB_PASS']) ? '(vide)' : '(defini)') . "\n";
echo '  ROOT_PATH = ' . (defined('ROOT_PATH') ? ROOT_PATH : '?') . "\n";

echo "\nConnexion PDO :\n";
try {
    $pdo = getDatabaseConnection();
    echo "  OK\n";
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    echo '  MySQL : ' . $version . "\n";
    $dbName = $pdo->query('SELECT DATABASE()')->fetchColumn();
    echo '  Base active : ' . $dbName . "\n";
} catch (Throwable $e) {
    echo '  ECHEC : ' . $e->getMessage() . "\n";
    echo "\n>>> Corrigez .env ou son emplacement, puis supprimez ce fichier.\n";
    exit;
}

echo "\nTable admin_users :\n";
try {
    $count = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
    echo '  Nombre de comptes : ' . $count . "\n";
    if ($count === 0) {
        echo "  >>> Aucun admin ! Executez data/sql/seed_admin_user.sql dans phpMyAdmin.\n";
    } else {
        $rows = $pdo->query('SELECT id, username, CHAR_LENGTH(password_hash) AS hash_len, created_at FROM admin_users')->fetchAll();
        foreach ($rows as $row) {
            echo sprintf(
                "  - id=%d username=%s hash_len=%d created=%s\n",
                (int) $row['id'],
                (string) $row['username'],
                (int) $row['hash_len'],
                (string) $row['created_at']
            );
            if ((int) $row['hash_len'] < 60) {
                echo "    >>> hash trop court (doit faire ~60 caracteres bcrypt)\n";
            }
        }
    }
} catch (Throwable $e) {
    echo '  ERREUR : ' . $e->getMessage() . "\n";
    echo "  >>> La table admin_users existe-t-elle ? (admin_v1.sql)\n";
}

echo "\nTest mot de passe (optionnel) :\n";
echo "  POST ?key=sahp-diag-2026&test_user=admin&test_pass=VOTRE_MDP pour verifier le hash.\n";
if (isset($_GET['test_user'], $_GET['test_pass'])) {
    $testUser = strtolower(trim((string) $_GET['test_user']));
    $testPass = (string) $_GET['test_pass'];
    $stmt = $pdo->prepare('SELECT password_hash FROM admin_users WHERE username = :u LIMIT 1');
    $stmt->execute(['u' => $testUser]);
    $hash = $stmt->fetchColumn();
    if ($hash === false) {
        echo "  Utilisateur « {$testUser} » introuvable en base.\n";
    } else {
        $ok = password_verify($testPass, trim((string) $hash));
        echo '  password_verify : ' . ($ok ? 'OK' : 'ECHEC (mauvais mot de passe ou hash invalide)') . "\n";
    }
}

echo "\n>>> SUPPRIMEZ public/admin/diagnostic-db.php apres diagnostic.\n";
