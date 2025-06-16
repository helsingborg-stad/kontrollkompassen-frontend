<?php

declare(strict_types=1);

namespace KoKoP\Action;

use KoKoP\Helper\AuthException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use \KoKoP\Helper\Validate as Validate;
use \KoKoP\Renderer\BladeTemplateRenderer;
use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Enums\AuthErrorReason;

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
        $body = $request->getParsedBody();
        $username = $body['username'] ?? false;
        $password = $body['password'] ?? false;

        $action = match (true) {
            !Validate::username($username) => 'login-error-username',
            !Validate::password($password) => 'login-error-password',
            default => null,
        };

        if ($action) {
            return $this->renderer
                ->template($response, self::class, [
                    'action' => $action,
                    'username' => $username,
                ])
                ->withStatus(400);
        }

        try {
            $this->services->getSessionService()->setSession(
                $this->services->getAuthService()->authenticate($username, $password)
            );

            return $response
                ->withHeader('Location', '/uppslag')
                ->withStatus(302);
        } catch (AuthException $e) {
            match (AuthErrorReason::from($e->getCode())) {
                AuthErrorReason::Unauthorized => $action = 'login-error-no-access',
                default => $action = 'login-error',
            };

            return $this->renderer
                ->template($response, self::class, [
                    'action' => $action,
                    'username' => $username,
                ])
                ->withStatus(403);
        }
    }
}
