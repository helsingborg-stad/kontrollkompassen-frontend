<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Stream;

use \KoKoP\Interfaces\AbstractStream;
use \KoKoP\Interfaces\AbstractConfig;

class FileStream implements AbstractStream
{
    public function __construct(private AbstractConfig $config) {}

    public function getStream(array $content): StreamInterface
    {
        try {
            $apiKey = $this->config->getValue('API_KEY', '123abc');
            $apiUrl = $this->config->getValue('API_URL', null);

            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => [
                        'Content-type: application/json',
                        'X-API-Key:' . $apiKey
                    ],
                    'content' => json_encode($content)
                ]
            ]);

            return new Stream(fopen($apiUrl, 'rb', false, $context));
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create file stream: ' . $e->getMessage(), 0, $e);
        }
    }
}
