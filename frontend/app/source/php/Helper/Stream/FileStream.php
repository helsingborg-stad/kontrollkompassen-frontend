<?php

declare(strict_types=1);

namespace KoKoP\Helper\Stream;

use Psr\Http\Message\StreamInterface;

use \KoKoP\Interfaces\AbstractStream;

class FileStream implements AbstractStream
{
    private StreamInterface $stream;

    public function __construct(
        private string $apiKey,
        private mixed $apiUrl
    ) {}

    public function fetch(array $content): StreamInterface
    {
        $this->stream = StreamFactory::create($this->apiUrl, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => [
                    'content-type: application/json',
                    'x-api-key:' . $this->apiKey
                ],
                'content' => json_encode($content)
            ]
        ]));

        return $this->stream;
    }

    public function getContentType(): string
    {
        return $this->_getHeaderData('content-type');
    }

    public function getFilename(): string
    {
        $contentDisposition = $this->_getHeaderData('content-disposition');

        $pattern = str_contains($contentDisposition, 'filename*=')
            ? '/filename\\*=UTF-8\'\'([^;\\r\\n]+)/i'
            : '/filename="([^"]+)"/i';
        preg_match($pattern, $contentDisposition, $matches);

        return isset($matches[1])
            ? $matches[1]
            : '';
    }

    private function _getHeaderData($key): string
    {
        $wrapperData = $this->stream->getMetadata('wrapper_data');
        $header = array_find($wrapperData, fn($header) => str_starts_with(strtolower($header), strtolower($key) . ':'));
        return $header
            ? trim(explode(':', $header, 2)[1])
            : '';
    }
}
