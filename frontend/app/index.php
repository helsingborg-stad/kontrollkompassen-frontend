<?php

//Enable/disable all errors
if (isset($_GET['debug'])) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

//Run application
require_once 'Bootstrap.php';

//Get config & run, or run without config
$configFile = __DIR__ . '/../config.json';
if (file_exists($configFile)) {
	new \KoKoP\App(
		(array) json_decode(file_get_contents($configFile))
	);
} else {
	new \KoKoP\App();
}
