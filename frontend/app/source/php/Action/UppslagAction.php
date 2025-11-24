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
            'checked' => true,
        ],
        [
            'id' => 'bv',
            'label' => 'Bolagsverket',
            'checked' => true,
        ],
        [
            'id' => 'creditsafe',
            'label' => 'CreditSafe',
            'checked' => true,
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

        $userGroups = $session->getUser()->getGroups();
        $isBasicUser = empty($userGroups);

        if ($isBasicUser) {
            return $response
                ->withHeader('Location', '/uppslag-basic')
                ->withStatus(302);
        }

        return $this->renderer->template(
            $response,
            self::class,
            [
                'action' => null,
                'services' => self::SELECTABLE_SERVICES,
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
                        self::SELECTABLE_SERVICES
                    ),
                    'errorMessage' => $e->getMessage()
                ]
            );
        }
    }
}
