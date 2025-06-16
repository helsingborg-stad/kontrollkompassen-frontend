<?php

declare(strict_types=1);

namespace KoKoP\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use \KoKoP\Helper\Validate as Validate;
use \KoKoP\Renderer\BladeTemplateRenderer;
use \KoKoP\Interfaces\AbstractServices;

final class LoginAction
{
    public function __construct(
        private AbstractServices $services,
        private BladeTemplateRenderer $renderer
    ) {}

    public function index(Response $response): Response
    {
        if ($this->services->getSessionService()->isValidSession()) {
            return $response
                ->withHeader('Location', '/uppslag')
                ->withStatus(302);
        }

        return $this->renderer->template(
            $response,
            self::class,
            ['action' => null]
        );
    }

    public function login(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();
        $username = $params['username'] ?? false;
        $password = $params['password'] ?? false;

        $action = match (true) {
            !Validate::username($username) => 'login-error-username',
            !Validate::password($password) => 'login-error-password',
            default => null,
        };

        if ($action) {
            return $this->renderer
                ->template($response, self::class, ['action' => $action])
                ->withStatus(400);
        }

        $this->services->getSessionService()->setSession(
            $this->services->getAuthService()->authenticate($username, $password)
        );

        return $response
            ->withHeader('Location', '/uppslag')
            ->withStatus(302);
    }
}
