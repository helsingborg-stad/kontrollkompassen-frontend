<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(1);

define('BASEPATH', __DIR__ . '/');
define('VIEWS_PATH', BASEPATH . 'views/');
define('BLADE_CACHE_PATH', '/tmp/cache/');

(require_once __DIR__ . '/config_slim/bootstrap.php')->run();
