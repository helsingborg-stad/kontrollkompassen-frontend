<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Helper\MissingEnvKeysException;
use \KoKoP\Interfaces\AbstractRequiredEnvs;

class RequiredEnvs implements AbstractRequiredEnvs
{
    public function __construct(private array $requiredKeys) {}

    public function validate(array $env): void
    {
        $missing = [];

        foreach ($this->requiredKeys as $key) {
            if (!array_key_exists($key, $env) || $env[$key] === '' || $env[$key] === null) {
                $missing[] = $key;
            }
        }

        if (!empty($missing)) {
            throw new MissingEnvKeysException($missing);
        }
    }
}
