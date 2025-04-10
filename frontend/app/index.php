<?php

if (isset($_GET['debug'])) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

require_once 'Bootstrap.php';

use \KoKoP\App;

function main()
{
	$configFile = __DIR__ . '/../config.json';
	$app = new App(
		file_exists($configFile) ?
			(array) json_decode(file_get_contents($configFile)) : []
	);

	$app->loadPage();
}

main();
