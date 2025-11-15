<?php

declare(strict_types=1);

namespace KoKoP\Helper;

class MissingEnvKeysException extends \RuntimeException
{
    public function __construct(array $missingKeys)
    {
        parent::__construct(
            'Missing required environment keys: ' . implode(', ', $missingKeys)
        );
    }
}
