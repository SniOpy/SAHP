<?php

declare(strict_types=1);

/**
 * Limitation des tentatives de connexion admin : 5 échecs en 15 minutes par adresse IP.
 * Stockage fichier : data/security/login-attempts.json (dossier interdit en accès direct).
 */

const ADMIN_LOGIN_MAX_ATTEMPTS = 5;
const ADMIN_LOGIN_WINDOW_SECONDS = 900;

function adminLoginAttemptsFilePath(): string
{
    return dirname(__DIR__, 3) . '/data/security/login-attempts.json';
}

function adminLoginClientKey(): string
{
    return hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . '|sahp-admin');
}

/**
 * Lit, modifie puis réécrit le fichier des tentatives sous verrou exclusif.
 *
 * @param callable(array<string, list<int>>): array<string, list<int>> $mutate
 * @return array<string, list<int>>
 */
function adminLoginAttemptsUpdate(?callable $mutate = null): array
{
    $path = adminLoginAttemptsFilePath();
    $dir = dirname($path);
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    $handle = @fopen($path, 'c+');
    if ($handle === false) {
        return [];
    }

    try {
        flock($handle, $mutate === null ? LOCK_SH : LOCK_EX);
        $raw = stream_get_contents($handle);
        $data = json_decode((string) $raw, true);
        $data = is_array($data) ? $data : [];

        $now = time();
        foreach ($data as $key => $list) {
            $data[$key] = array_values(array_filter((array) $list, static fn ($t) => (int) $t > $now - ADMIN_LOGIN_WINDOW_SECONDS));
            if ($data[$key] === []) {
                unset($data[$key]);
            }
        }

        if ($mutate !== null) {
            $data = $mutate($data);
            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, (string) json_encode($data));
            fflush($handle);
        }

        return $data;
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

function adminLoginIsBlocked(): bool
{
    $data = adminLoginAttemptsUpdate();

    return count($data[adminLoginClientKey()] ?? []) >= ADMIN_LOGIN_MAX_ATTEMPTS;
}

function adminLoginRecordFailure(): void
{
    $key = adminLoginClientKey();
    adminLoginAttemptsUpdate(static function (array $data) use ($key): array {
        $data[$key][] = time();

        return $data;
    });
}

function adminLoginResetAttempts(): void
{
    $key = adminLoginClientKey();
    adminLoginAttemptsUpdate(static function (array $data) use ($key): array {
        unset($data[$key]);

        return $data;
    });
}
