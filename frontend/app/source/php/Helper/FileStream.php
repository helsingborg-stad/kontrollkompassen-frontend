<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Stream;

use \KoKoP\Interfaces\AbstractStream;

function encodeRFC7230(string $input): string
{
    $encoded = '';
    $allowedPattern = '@^[ \t\x21-\x7E\x80-\xFF]*$@D';

    for ($i = 0; $i < strlen($input); $i++) {
        $char = $input[$i];
        $ascii = ord($char);

        if (preg_match($allowedPattern, $char) === 1) {
            $encoded .= $char;
        } else {
            $encoded .= sprintf('%%%02X', $ascii);
        }
    }

    return $encoded;
}

class FileStream implements AbstractStream
{
    private string $apiUrl;
    private string $apiKey;
    private StreamInterface $stream;

    public function __construct(string $apiKey, string $apiUrl)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
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
        $value = current(
            array_filter(
                $this->stream->getMetadata('wrapper_data'),
                fn($item) => str_starts_with($item, 'content-type')
            )
        );

        return $value !== false && str_contains($value, ':')
            ? explode(':', $value)[1]
            : false;
    }

    public function getFilename(): string | bool
    {
        $contentDisposition = current(
            array_filter(
                $this->stream->getMetadata('wrapper_data'),
                fn($item) => str_starts_with($item, 'content-disposition')
            )
        );

        if (str_contains($contentDisposition, 'filename*=')) {
            preg_match(
                "/filename\\*=(?:UTF-8'')?([^;\\r\\n]+)/i",
                $contentDisposition,
                $matches
            );

            return isset($matches[1])
                ? encodeRFC7230($matches[1])
                : false;
        }

        preg_match(
            '/filename="([^"]+)"/',
            $contentDisposition,
            $matches
        );

        return encodeRFC7230($matches[1]) ?? false;
    }
}
