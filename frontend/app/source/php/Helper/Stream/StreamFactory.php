<?php

declare(strict_types=1);

namespace KoKoP\Helper\Stream;

use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Stream as SlimStream;

use \KoKoP\Helper\Stream\NullStream;

class StreamFactory
{
    public static function create(mixed $url, mixed $context): StreamInterface
    {
        return self::_validateUrl($url)
            ? new SlimStream(fopen($url, 'rb', false, $context))
            : new NullStream();
    }

    private static function _validateUrl(mixed $u): bool
    {
        return is_string($u) && filter_var($u, FILTER_VALIDATE_URL) !== false;
    }
}
