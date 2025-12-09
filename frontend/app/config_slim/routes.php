<?php

declare(strict_types=1);

use Slim\App;

use \KoKoP\Action\HomeAction;
use \KoKoP\Action\LoginAction;
use \KoKoP\Action\LogoutAction;
use \KoKoP\Action\UppslagAction;
use \KoKoP\Action\UppslagBasicAction;
use \KoKoP\Action\ForgotPasswordAction;
use \KoKoP\Action\AdminAction;

return function (App $app): void {
    $app->get('/', HomeAction::class);

    $app->get('/login', [LoginAction::class, 'index']);
    $app->post('/login', [LoginAction::class, 'login']);

    $app->get('/logout', [LogoutAction::class, 'logout']);

    $app->get('/uppslag', [UppslagAction::class, 'index']);
    $app->post('/uppslag', [UppslagAction::class, 'fetch']);

    $app->get('/uppslag-enkel', [UppslagBasicAction::class, 'index']);
    $app->post('/uppslag-enkel', [UppslagBasicAction::class, 'fetch']);

    $app->get('/glomt-losenord', ForgotPasswordAction::class);

    $app->get('/admin', AdminAction::class);
    $app->post('/admin/downloadHistory', [AdminAction::class, 'downloadHistory']);
};
