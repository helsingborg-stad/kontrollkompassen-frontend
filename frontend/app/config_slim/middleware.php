<?php

declare(strict_types=1);

namespace KoKoP\Middleware;

use Slim\App;
use Slim\Middleware\Session as SlimSessionMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->add(SlimSessionMiddleware::class);
    $app->addErrorMiddleware(true, true, true);
};
