<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractRequest;
use \KoKoP\Interfaces\AbstractSession;
use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Interfaces\AbstractCheckOrgNo;

class CheckOrgNo implements AbstractCheckOrgNo
{
    protected string $baseUrl;
    protected string $apiKey;
    protected AbstractRequest $request;
    protected AbstractSession $session;

    public function __construct(AbstractConfig $config, AbstractRequest $request, AbstractSession $session)
    {
        $this->baseUrl = $config->getValue('BACKEND_BASE_URL', 'http://localhost:8000');
        $this->apiKey = $config->getValue('BACKEND_API_KEY', '');
        $this->request = $request;
        $this->session = $session;
    }

    public function getDetails(string $orgNo): array
    {
        return [
            'searchResult' => [
                'orgNo' => $orgNo,
                'name' => 'Example Organization',
                'address' => '123 Example St, Example City',
                'status' => 'active'
            ]
        ];
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getEndpoint(): string
    {
        return $this->baseUrl;
    }

    protected function fetchOrg()
    {
        return $this->request->post(
            $this->getEndpoint() . '/api/fetch',
            [
                'orgNo' => '123456789',
                'apiKey' => $this->getApiKey()
            ]
        );
    }
}
