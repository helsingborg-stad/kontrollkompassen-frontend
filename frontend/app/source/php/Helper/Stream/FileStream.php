<?php

declare(strict_types=1);

namespace KoKoP\Helper\Stream;

use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Stream;

use \KoKoP\Interfaces\AbstractStream;

use function \KoKoP\Utils\encodeRFC7230;

class FileStream implements AbstractStream
{
    private string $apiUrl;
    private string $apiKey;
    private StreamInterface $stream;

    public function __construct(string $apiKey, string $apiUrl, StreamInterface $stream)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
        $this->stream = $stream;
    }

    public function fetch(array $content): StreamInterface
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

            $this->stream = new Stream(fopen($this->apiUrl, 'rb', false, $context));

            return $this->stream;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create file stream: ' . $e->getMessage(), 0, $e);
        }
    }

    public function getContentType(): string | bool
    {
        return $this->stream->getMetadata('wrapper_data')['content-type'] ?? false;
    }

    public function getFilename(): string | bool
    {
        $contentDisposition = $this->stream->getMetadata('wrapper_data')['content-disposition'];
        if (!$contentDisposition) {
            return false;
        }

        $pattern = str_contains($contentDisposition, 'filename*=')
            ? '/filename\\*=UTF-8\'\'([^;\\r\\n]+)/i'
            : '/filename="([^"]+)"/i';

        preg_match($pattern, $contentDisposition, $matches);

        return isset($matches[1])
            ? encodeRFC7230($matches[1])
            : false;
    }
}
