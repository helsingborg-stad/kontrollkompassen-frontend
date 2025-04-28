<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

/**
 * Wrapper class for donwnloadable content
 */
interface AbstractLink
{
    public function getDownloadUrl(): string;
    public function getFileName(): string|null;
    public function getFileSize(): int;
}
