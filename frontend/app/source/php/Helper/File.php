<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\MessageInterface;

use \KoKoP\Interfaces\AbstractFile;
use \KoKoP\Interfaces\AbstractConfig;

class File implements AbstractFile
{
    protected AbstractConfig $config;
    protected array $requestData;

    public function __construct(AbstractConfig $config, array $requestData)
    {
        $this->config = $config;
        $this->requestData = $requestData;
    }

    public function getFileData(Response $response): MessageInterface
    {
        $apiKey = $this->config->getValue('API_KEY', '123abc');
        $orgNo = $this->requestData['orgNo'] ?? '1111222233';
        $email = $this->requestData['user']->getEmail() ?? null;

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => ['Content-type: application/json', 'X-API-Key:' . $apiKey],
                'content' => json_encode([
                    'orgNo' => $orgNo,
                    'email' => $email,
                ])
            ]
        ]);

        $newResponse = $response->withBody(new \Slim\Psr7\Stream(fopen('http://localhost:8000/api/export', 'rb', false, $context)));
        return $newResponse->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function getFileName(): string
    {
        return 'example.txt';
    }

    public function getFileSize(): int
    {
        return 1024;
    }
}
