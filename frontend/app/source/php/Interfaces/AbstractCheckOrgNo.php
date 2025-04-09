<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractCheckOrgNo
{
    public function getDetails(string $orgNo): array;
    public function getApiKey(): string;
    public function getEndpoint(): string;
}
