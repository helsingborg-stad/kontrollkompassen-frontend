<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractResponse
{
    public function getStatusCode(): int;
    public function getContent(): ?object;
    public function getHash(): ?string;
    public function isErrorResponse(): bool;
}
