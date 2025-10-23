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
    private const SELECTABLE_SERVICES = [
        [
            'id' => 'amv',
            'label' => 'Arbetsmiljöverket',
            'checked' => false,
        ],
        [
            'id' => 'bv',
            'label' => 'Bolagsverket',
            'checked' => true,
        ],
        [
            'id' => 'creditsafe',
            'label' => 'CreditSafe',
            'checked' => false,
        ],
        [
            'id' => 'kapitel13',
            'label' => 'Kapitel13',
            'checked' => true,
        ],
        [
            'id' => 'shv',
            'label' => 'Svensk Handel Varningslista',
            'checked' => true,
        ]
    ];

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
                'services' => self::SELECTABLE_SERVICES,
            ]
        );
    }

    public function fetch(Request $request, Response $response): Response
    {
        try {
            $orgNo = $request->getParsedBody()['orgno'];
            $selectedServices = $request->getParsedBody()['selectedservices'];

            $service = $this->services->getOrganizationService();

            return $service->generateDownload(
                $response,
                $this->services->getSessionService()->getUser(),
                $service->validateOrgNo($orgNo),
                $selectedServices
            );
        } catch (OrganizationException $e) {
            return $this->renderer->template(
                $response->withStatus($e->getDetails()['httpErrorCode']),
                self::class,
                [
                    'user' => $this->services->getSessionService()->getUser(),
                    'formattedUser' => $this->services->getSessionService()->getUser()->format(),
                    'action' => 'orgno-malformed',
                    'orgNo' => $orgNo ?? null,
                    'services' => array_map(
                        function ($service) use ($selectedServices) {
                            $service['checked'] = in_array($service['id'], $selectedServices ?? [], true);
                            return $service;
                        },
                        self::SELECTABLE_SERVICES
                    ),
                    'errorMessage' => $e->getMessage(),
                    'previousException' => $e->getPrevious() ? $e->getPrevious()->getMessage() : null,
                ]
            );
        }
    }
}
