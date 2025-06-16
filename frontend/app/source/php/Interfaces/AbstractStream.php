<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

use Psr\Http\Message\StreamInterface;

interface AbstractStream
{
    public function getStream(array $content): StreamInterface;
}
