<?php

if (isset($_GET['debug'])) {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

define('BASEPATH', dirname(__FILE__) . '/');
define('VIEWS_PATH', BASEPATH . 'views/');
define('BLADE_CACHE_PATH', '/tmp/cache/');
define('LOCAL_DOMAIN', '.local');

if (file_exists(BASEPATH . 'vendor/autoload.php')) {
  require BASEPATH . 'vendor/autoload.php';
}
