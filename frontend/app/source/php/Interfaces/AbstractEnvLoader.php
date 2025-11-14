<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;


interface AbstractEnvLoader
{
    public function load(): array;
}
