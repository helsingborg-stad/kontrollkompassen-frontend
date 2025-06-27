<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Stream;

use \KoKoP\Interfaces\AbstractStream;

class FileStream implements AbstractStream
{
    private string $apiUrl;
    private string $apiKey;

    public function __construct(string $apiKey, string $apiUrl)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
    }

    public function getStream(array $content): StreamInterface
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => [
                        'content-type: application/json',
                        'x-api-key:' . $this->apiKey
                    ],
                    'content' => json_encode($content)
                ]
            ]);

            return new Stream(fopen($this->apiUrl, 'rb', false, $context));
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create file stream: ' . $e->getMessage(), 0, $e);
        }
    }
}
