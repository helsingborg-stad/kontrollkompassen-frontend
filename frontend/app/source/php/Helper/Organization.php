<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractRequest;
use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Interfaces\AbstractLink;
use \KoKoP\Interfaces\AbstractOrganization;
use \KoKoP\Interfaces\AbstractUser;

class Organization implements AbstractOrganization
{
    protected AbstractConfig $config;
    protected AbstractRequest $request;

    public function __construct(AbstractConfig $config, AbstractRequest $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    public function getLink(string $orgNo, AbstractUser $user): AbstractLink
    {
        $baseUrl = $this->config->getValue('BACKEND_BASE_URL', 'http://localhost:8000');
        $apiKey = $this->config->getValue('API_KEY', '');

        return new Link($this->config, $this->request->post(
            $baseUrl . '/api/export',
            [
                'orgNo' => $orgNo,
                'groups' => $user->getGroups(),
                'email' => $user->getMailAddress()
            ],
            [
                'X-API-Key' => $apiKey
            ]
        ));
    }
}
