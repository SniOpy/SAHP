<?php

$token = $_GET['token'] ?? '';
if ($token !== 'CHANGE_ME_SECRET_TOKEN') {
    http_response_code(403);
    exit('Forbidden');
}

$cacheDir = __DIR__ . '/../data/cache/html';

if (!is_dir($cacheDir)) exit("No cache dir");

$files = glob($cacheDir . '/*.html');
foreach ($files as $f) {
    @unlink($f);
}

echo "Cache purged: " . count($files);
