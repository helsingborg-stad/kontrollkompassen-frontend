<?php

declare(strict_types=1);

namespace KoKoP\Middleware;

use KoKoP\Interfaces\AbstractServices;
use Slim\App;
use Slim\Middleware\Session as SlimSessionMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->add(SlimSessionMiddleware::class);

    // Only expose exception details in HTTP responses when DEBUG=true; errors
    // are always written to the log.
    $displayErrorDetails = $app->getContainer()
        ->get(AbstractServices::class)
        ->getConfigService()
        ->getValue('DEBUG') === true;

    $app->addErrorMiddleware($displayErrorDetails, true, true);
};
