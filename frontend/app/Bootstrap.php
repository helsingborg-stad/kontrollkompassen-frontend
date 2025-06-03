<?php

if (isset($_GET['debug'])) {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

define('BASEPATH', dirname(__FILE__) . '/');

if (file_exists(BASEPATH . 'vendor/autoload.php')) {
  require BASEPATH . 'vendor/autoload.php';
}
