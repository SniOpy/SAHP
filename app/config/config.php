<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| ENVIRONNEMENT
|--------------------------------------------------------------------------
| true  = développement (local)
| false = production
*/
define('APP_ENV', false); 

/*
|--------------------------------------------------------------------------
| BASE URL
|--------------------------------------------------------------------------
*/
if (APP_ENV === true) {
    // WAMP / localhost
    define('BASE_URL', '/sahp/public');
} else {
    // PROD (racine du domaine)
    define('BASE_URL', '');
}

/*
|--------------------------------------------------------------------------
| PATHS
|--------------------------------------------------------------------------
*/
define('ROOT_PATH', dirname(__DIR__, 2));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/Views');
