<?php

declare(strict_types=1);

require_once 'Bootstrap.php';
require_once 'Factory.php';


function main(): void
{
	$app = createAppFromConfig(__DIR__ . '/../config.json');
	$app->loadPage();
}

main();
