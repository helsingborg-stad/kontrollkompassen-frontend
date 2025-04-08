<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractConfig
{
    public function getValue(string $key, mixed $default = null): mixed;
    public function getValues(): array;
}
