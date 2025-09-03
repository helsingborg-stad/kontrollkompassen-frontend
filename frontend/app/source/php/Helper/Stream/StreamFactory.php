<?php

declare(strict_types=1);

namespace KoKoP\Helper\Stream;

use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Stream as SlimStream;

use \KoKoP\Helper\Stream\MockRawStream;

class StreamFactory
{
    public static function createFromEnv(string $env, $resource): StreamInterface
    {
        return match ($env) {
            'slim' => new SlimStream($resource),
            'mock' => new MockRawStream($resource),
            default => throw new \InvalidArgumentException("Unsupported stream environment: $env"),
        };
    }
}
