<?php

declare(strict_types=1);

namespace KoKoP\Action;

use \KoKoP\Renderer\BladeTemplateRenderer;
use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Helper\Stream\FileStream;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \KoKoP\Helper\Organization\OrganizationException;
use \KoKoP\Enums\OrganizationErrorReason;

final class AdminAction
{
    public function __construct(
        private AbstractServices $services,
        private BladeTemplateRenderer $renderer
    ) {}

    public function __invoke(Response $response): Response
    {
        if (!$this->services
            ->getSessionService()
            ->isValidSession()) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        $userGroups = $this->services->getSessionService()->getUser()->getGroups();
        $isBasicUser = empty($userGroups);

        if ($isBasicUser) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        return $this->renderer
            ->template(
                $response,
                self::class
            );
    }

    public function downloadHistory(Request $request, Response $response)
    {
        if (!$this->services
            ->getSessionService()
            ->isValidSession()) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        $userGroups = $this->services->getSessionService()->getUser()->getGroups();
        $isBasicUser = empty($userGroups);

        if ($isBasicUser) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        try {
            $config = $this->services->getConfigService();

            $fileStream = new FileStream(
                $config->getValue('API_KEY', '123abc'),
                $config->getValue('API_URL', '') . '/history/export/csv'
            );

            $responseWithBody = $response->withBody($fileStream->fetch([]));

            return $responseWithBody
                ->withHeader(
                    'Content-Type',
                    $fileStream->getContentType() ?: "text/csv"
                )
                ->withHeader(
                    'Content-Disposition',
                    "attachment; filename*=UTF-8''" . ($fileStream->getFilename() ?: "history.csv")
                );
        } catch (\TypeError $e) {
            throw new OrganizationException(OrganizationErrorReason::InvalidLengthSelected);
        } catch (\Exception $e) {
            throw new OrganizationException(OrganizationErrorReason::ServiceError, $e);
        }
    }
}
