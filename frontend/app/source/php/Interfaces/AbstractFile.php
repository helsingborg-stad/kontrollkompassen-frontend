<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface as Response;

interface AbstractFile
{
    public function getFileData(Response $response): MessageInterface;
    public function getFileName(): string | null;
    public function getFileSize(): int;
}
