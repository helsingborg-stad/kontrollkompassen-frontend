<?php

declare(strict_types=1);

require_once BASE_PATH . 'vendor/autoload.php';

use DI\ContainerBuilder;
use DI\Bridge\Slim\Bridge;

$container = new ContainerBuilder()
    ->addDefinitions(BASE_PATH . 'config_slim/container.php')
    ->build();

$app = Bridge::create($container);

(require_once BASE_PATH . 'config_slim/middleware.php')($app);
(require_once BASE_PATH . 'config_slim/routes.php')($app);

return $app;
