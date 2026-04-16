<?php

declare(strict_types=1);

require_once dirname(__DIR__, 3) . '/app/helpers/database.php';

/**
 * Retourne une connexion PDO unique pour l'espace admin.
 */
function getDatabaseConnection(): PDO
{
    return getAppDatabaseConnection();
}
