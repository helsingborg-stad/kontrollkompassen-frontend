<?php

declare(strict_types=1);

namespace KoKoP\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use \KoKoP\Renderer\BladeTemplateRenderer;
use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Helper\Organization\OrganizationException;


final class UppslagAction
{
    public function __construct(
        private AbstractServices $services,
        private BladeTemplateRenderer $renderer
    ) {}

    public function index(Response $response): Response
    {
        $session = $this->services->getSessionService();

        if (!$session->isValidSession()) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        return $this->renderer->template(
            $response,
            self::class,
            [
                'user' => $session->getUser(),
                'formattedUser' => $session->getUser()->format(),
                'action' => null,
            ]
        );
    }

    public function fetch(Request $request, Response $response): Response
    {
        $orgNo = $request->getParsedBody()['orgno'];

        return $this->services
            ->getOrganizationService()
            ->generateDownload(
                $response,
                $this->services->getSessionService()->getUser(),
                $orgNo
            );
    }
}
