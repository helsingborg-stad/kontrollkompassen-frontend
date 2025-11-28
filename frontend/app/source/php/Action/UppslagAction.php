<?php

declare(strict_types=1);

namespace KoKoP\Action;

use KoKoP\Enums\OrganizationErrorReason;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use \KoKoP\Renderer\BladeTemplateRenderer;
use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Helper\Organization\OrganizationException;
use KoKoP\Helper\Request as HelperRequest;


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

        $userGroups = $session->getUser()->getGroups();
        $isBasicUser = empty($userGroups);

        if ($isBasicUser) {
            return $response
                ->withHeader('Location', '/uppslag-enkel')
                ->withStatus(302);
        }

        return $this->renderer->template(
            $response,
            self::class,
            [
                'action' => null,
                'services' => self::getServicesList(),
            ]
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

            $orgNo = @$request->getParsedBody()['orgno'];
            $selectedServices = @$request->getParsedBody()['selectedservices'];

            $orgService = $this->services->getOrganizationService();

            return $orgService->generateDownload(
                $response,
                $this->services->getSessionService()->getUser(),
                $orgService->validateOrgNo($orgNo),
                $selectedServices
            );
        } catch (OrganizationException $e) {
            return $this->renderer->template(
                $response->withStatus($e->getDetails()['httpErrorCode']),
                self::class,
                [
                    'action' => 'orgno-malformed',
                    'orgNo' => $orgNo ?? null,
                    'services' => array_map(
                        function ($s) use ($selectedServices) {
                            $s['checked'] = in_array($s['id'], $selectedServices ?? [], true);
                            return $s;
                        },
                        self::getServicesList()
                    ),
                    'errorMessage' => $e->getMessage()
                ]
            );
        }
    }

    private function getServicesList(): array
    {
        $config = $this->services->getConfigService();
        $apiKey = $config->getValue('API_KEY', '');

        $servicesUrl = $config->getValue('API_URL', '') . '/services';
        $servicesListRequest = new HelperRequest();
        $servicesListResponse = $servicesListRequest->get($servicesUrl, [
            'mode' => 'advanced'
        ], [
            'X-API-KEY' => $apiKey,
            'content-type' => 'application/json'
        ]);
        if ($servicesListResponse->getStatusCode() !== 200) {
            throw new OrganizationException(OrganizationErrorReason::ServiceError);
        }

        $servicesListData = $servicesListResponse->getContent();
        $services = [];

        foreach ($servicesListData as $value) {
            $services[] = [
                'id' => $value->id,
                'label' => $value->label,
                'checked' => $value->checked,
            ];
        }

        return $services;
    }
}
