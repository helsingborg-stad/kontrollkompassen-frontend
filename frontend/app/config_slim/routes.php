<?php

declare(strict_types=1);

use Slim\App;

use \KoKoP\Action\HomeAction;
use \KoKoP\Action\LoginAction;
use \KoKoP\Action\LogoutAction;
use \KoKoP\Action\UppslagAction;
use \KoKoP\Action\ForgotPasswordAction;

return function (App $app): void {
    $app->get('/', [HomeAction::class, 'index']);

    $app->get('/login', [LoginAction::class, 'index']);
    $app->post('/login', [LoginAction::class, 'login']);

    $app->get('/logout', [LogoutAction::class, 'logout']);

    $app->get('/uppslag', [UppslagAction::class, 'index']);
    $app->post('/uppslag', [UppslagAction::class, 'fetch']);
    $app->get('/uppslag/success', [UppslagAction::class, 'success']);

    $app->get('/glomt-losenord', [ForgotPasswordAction::class, 'index']);
};
