<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractRequest;
use \KoKoP\Interfaces\AbstractConfig;
use KoKoP\Interfaces\AbstractLink;
use KoKoP\Interfaces\AbstractOrganization;
use KoKoP\Interfaces\AbstractUser;

class Organization implements AbstractOrganization
{
    protected string $baseUrl;
    protected string $apiKey;
    protected AbstractRequest $request;

    public function __construct(AbstractConfig $config, AbstractRequest $request)
    {
        $this->baseUrl = $config->getValue('BACKEND_BASE_URL', 'http://localhost:8000');
        $this->apiKey = $config->getValue('BACKEND_API_KEY', '');
        $this->request = $request;
    }

    public function getLink(string $orgNo, AbstractUser $as): AbstractLink
    {
        return  new Link(
            new Response(200, '', (object) array((object)[
                "url" => "http://google.se",
                "name" => "Filename",
                "size" => 123
            ]))
        );
        /*        return new Link($this->request->post(
            $this->baseUrl . '/api/fetch',
            [
                'orgNo' => '123456789',
                'apiKey' => $this->apiKey,
                'groups' => $as->getGroups(),
                'email' => $as->getMailAddress()
            ]
        ));*/
    }
}
