<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use DI\Bridge\Slim\Bridge;

require_once __DIR__ . '/../vendor/autoload.php';

$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/container.php')
    ->build();

$app = Bridge::create($container);

(require_once __DIR__ . '/middleware.php')($app);
(require_once __DIR__ . '/routes.php')($app);

return $app;
