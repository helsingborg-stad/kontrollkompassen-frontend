<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

use Psr\Http\Message\StreamInterface;

interface AbstractStream
{
    public function fetch(array $content): StreamInterface;
    public function getContentType(): string | bool;
    public function getFilename(): string | bool;
}
