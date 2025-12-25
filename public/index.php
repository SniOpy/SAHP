<?php

require_once __DIR__ . '/../app/Core/Router.php';

$url = $_GET['url'] ?? '';

$router = new Router();
$router->dispatch($url);
