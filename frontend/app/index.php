<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(1);

define('BASE_PATH', __DIR__ . '/');
define('VIEWS_PATH', BASE_PATH . 'views/');
define('BLADE_CACHE_PATH', '/tmp/cache/');

(require_once BASE_PATH . 'config_slim/bootstrap.php')->run();
