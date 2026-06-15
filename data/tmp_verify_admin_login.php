<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../public/admin/includes/auth.php';

$candidates = [
    'Content123!',
    'Content123',
    '94Pedzou94',
    'ChangeMe123!',
    'admin',
];

try {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->query('SELECT id, username, password_hash, CHAR_LENGTH(password_hash) AS hash_len FROM admin_users');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rows === []) {
        echo "Aucun compte admin en base.\n";
        exit(1);
    }

    foreach ($rows as $row) {
        echo 'id=' . $row['id'] . ' username=' . $row['username'] . ' hash_len=' . $row['hash_len'] . PHP_EOL;
        $hash = trim((string) $row['password_hash']);
        $matched = false;
        foreach ($candidates as $password) {
            if (password_verify($password, $hash)) {
                echo '  password_match: ' . $password . PHP_EOL;
                $matched = true;
            }
        }
        if (! $matched) {
            echo "  password_match: aucun des mots de passe testes\n";
        }

        $adminUser = findAdminUserByUsername($row['username']);
        echo '  findAdminUserByUsername: ' . ($adminUser !== null ? 'OK' : 'ECHEC') . PHP_EOL;
        foreach ($candidates as $password) {
            if ($adminUser !== null && verifyAdminPassword($password, (string) $adminUser['password_hash'])) {
                echo '  verifyAdminPassword OK pour: ' . $password . PHP_EOL;
            }
        }
    }
} catch (Throwable $e) {
    echo 'ECHEC: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
