<?php

declare(strict_types=1);

namespace KoKoP\Action;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use \KoKoP\Renderer\BladeTemplateRenderer;
use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Helper\Organization\OrganizationException;
use \KoKoP\Enums\OrganizationErrorReason;
use KoKoP\Helper\Request as HelperRequest;

final class UppslagBasicAction
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
            []
        );
    }

    public function fetch(Request $request, Response $response): Response
    {
        try {
            $session = $this->services->getSessionService();

            if (!$session->isValidSession()) {
                return $response
                    ->withHeader('Location', '/')
                    ->withStatus(302);
            }

            $orgNo = $request->getParsedBody()['orgno'];

            $config = $this->services->getConfigService();
            $apiUrl = $config->getValue('API_URL', '') . '/export/json';
            $apiKey = $config->getValue('API_KEY', '');
            $user = $this->services->getSessionService()->getUser();

            $postRequest = new HelperRequest();
            $postResponse = $postRequest->post($apiUrl, [
                'orgNo' => (string) $orgNo,
                'email' => $user->getMailAddress(),
                'groups' => $user->getGroups(),
                'services' => ['amv', 'bv', 'creditsafe', 'kapitel13', 'shv'],
            ], [
                'x-api-key' => $apiKey,
                'content-type' => 'application/json'
            ]);

            $responseData = $postResponse->getContent();

            if ($postResponse->getStatusCode() !== 200 || !$responseData->data) {
                throw new OrganizationException(OrganizationErrorReason::ServiceError);
            }

            $data = $responseData->data;

            $orgName = $data->orgName;

            $checksAllZero = false;
            $checks = $data->checks ?? null;

            if ($checks !== null) {
                $hasAny = false;
                $allZero = true;
                foreach ((array) $checks as $value) {
                    $hasAny = true;
                    if ((int) $value !== 0) {
                        $allZero = false;
                        break;
                    }
                }
                $checksAllZero = $hasAny && $allZero;
            }

            return $this->renderer->template(
                $response,
                self::class,
                [
                    'action' => $checksAllZero ? 'basic-lookup-ok' : 'basic-lookup-bad',
                    'orgNo' => $orgNo,
                    'orgName' => $orgName,
                    'debugData' => json_encode($postResponse->getStatusCode()),
                ]
            );
        } catch (Exception $e) {
            return $this->renderer->template(
                $response->withStatus(500, $e->getMessage()),
                self::class,
                [
                    'action' => 'orgno-malformed',
                    'orgNo' => $orgNo ?? null,
                    'errorMessage' => $e->getMessage(),
                    'previousException' => $e->getPrevious() ? $e->getPrevious()->getMessage() : null,
                ]
            );
        }
    }
}
