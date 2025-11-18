<?php

declare(strict_types=1);

namespace KoKoP\Helper\Stream;

use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Stream as SlimStream;

use \KoKoP\Interfaces\AbstractRequest;
use \KoKoP\Helper\Stream\NullStream;

class StreamFactory
{
    public function __construct(
        private mixed $url,
        private mixed $context,
        private AbstractRequest $request
    ) {}

    public function create(): StreamInterface
    {
        return $this->_validateUrl()
            ? new SlimStream(fopen($this->url, 'rb', false, $this->context))
            : new NullStream();
    }

    private function _validateUrl(): bool
    {
        $baseUrl = parse_url($this->url, PHP_URL_SCHEME) .
            '://' . parse_url($this->url, PHP_URL_HOST) .
            (parse_url($this->url, PHP_URL_PORT) ? ':' . parse_url($this->url, PHP_URL_PORT) : '');

        return is_string($this->url)
            && filter_var($this->url, FILTER_VALIDATE_URL) !== false
            && $this->request->get($baseUrl . '/docs')->getStatusCode() === 200;
    }
}
