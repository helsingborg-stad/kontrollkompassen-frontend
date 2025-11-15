<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractRequiredEnvs
{
    /**
     * @throws MissingEnvKeysException
     */
    public function validate(array $env): void;
}
