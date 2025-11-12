<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(1);

// When using PHP's built-in web server for local development, act as a router
// and let the server serve existing static files directly. If the requested
// URI maps to a file on disk, returning false tells the built-in server to
// serve that file instead of routing the request through this script.
if (php_sapi_name() === 'cli-server') {
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . ($url['path'] ?? '');
    if ($file && is_file($file)) {
        return false;
    }
}

define('BASEPATH', __DIR__ . '/');
define('VIEWS_PATH', BASEPATH . 'views/');
define('BLADE_CACHE_PATH', '/tmp/cache/');

(require_once __DIR__ . '/config_slim/bootstrap.php')->run();
