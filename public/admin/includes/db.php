<?php

declare(strict_types=1);

/**
 * Retourne une connexion PDO unique pour l'espace admin.
 */
function getDatabaseConnection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $databaseHost = $_ENV['DB_HOST'] ?? 'localhost';
    $databasePort = $_ENV['DB_PORT'] ?? '';
    $databaseName = $_ENV['DB_NAME'] ?? '';
    $databaseUser = $_ENV['DB_USER'] ?? '';
    $databasePassword = $_ENV['DB_PASS'] ?? '';

    if ($databaseName === '' || $databaseUser === '') {
        throw new RuntimeException('Configuration base de donnees manquante.');
    }

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $databaseHost, $databasePort, $databaseName);

    $pdo = new PDO($dsn, $databaseUser, $databasePassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
