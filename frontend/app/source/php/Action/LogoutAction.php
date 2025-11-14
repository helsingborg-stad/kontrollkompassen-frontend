<?php

declare(strict_types=1);

namespace KoKoP\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use KoKoP\Interfaces\AbstractServices;

final class LogoutAction
{
    public function __construct(private AbstractServices $services) {}

    public function logout(Request $request, Response $response): Response
    {
        $this->services->getSessionService()->endSession();

        $request->withAttribute('action', 'logoutmsg');

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }
}
