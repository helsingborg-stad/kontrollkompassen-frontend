<?php

declare(strict_types=1);

namespace KoKoP\Action;

use Psr\Http\Message\ResponseInterface as Response;

use \KoKoP\Renderer\BladeTemplateRenderer;
use \KoKoP\Interfaces\AbstractServices;

final class ForgotPasswordAction
{
    public function __construct(
        private AbstractServices $services,
        private BladeTemplateRenderer $renderer
    ) {}

    public function __invoke(Response $response): Response
    {
        if ($this->services
            ->getSessionService()
            ->isValidSession()
        ) {
            return $response
                ->withHeader('Location', '/uppslag')
                ->withStatus(302);
        }

        return $this->renderer
            ->template(
                $response,
                self::class
            );
    }
}
