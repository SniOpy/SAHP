<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

/**
 * Connexion PDO partagée (front blog + admin).
 * Les identifiants viennent du fichier .env (chargé par app/config/config.php).
 */
function getAppDatabaseConnection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $databaseHost = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $databasePort = $_ENV['DB_PORT'] ?? '';
    $databaseName = $_ENV['DB_NAME'] ?? '';
    $databaseUser = $_ENV['DB_USER'] ?? '';
    $databasePassword = $_ENV['DB_PASS'] ?? '';

    if ($databaseName === '' || $databaseUser === '') {
        throw new RuntimeException('Configuration base de données manquante (DB_NAME, DB_USER).');
    }

    if ($databasePort === '') {
        $databasePort = '3306';
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $databaseHost,
        $databasePort,
        $databaseName
    );

    $pdo = new PDO($dsn, $databaseUser, $databasePassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
