<?php

declare(strict_types=1);

namespace KoKoP\Helper\Stream;

use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Stream as SlimStream;

use \KoKoP\Helper\Stream\NullStream;

class StreamFactory
{
    public const ENV_DEFAULT = 'slim';
    public const ENV_NULL = 'null';

    public static function createFromEnv(string $env, $resource): StreamInterface
    {
        return match ($env) {
            self::ENV_DEFAULT => new SlimStream($resource),
            self::ENV_NULL => new NullStream(),
            default => throw new \InvalidArgumentException("Unsupported stream environment: $env"),
        };
    }
}
