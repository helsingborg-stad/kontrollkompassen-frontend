<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractCache
{
    public function get(string $key): mixed;
    public function set(AbstractResponse $response, int $ttl = 300): void;
}
