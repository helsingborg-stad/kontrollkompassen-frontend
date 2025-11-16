<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Helper\MissingEnvKeysException;
use \KoKoP\Interfaces\AbstractRequiredEnvs;

const REQUIRED_ENVS = [
    'API_URL',
    'API_KEY',
    'MS_AUTH',
    'ENCRYPT_VECTOR',
    'ENCRYPT_KEY',
    'ENCRYPT_CIPHER',
    'PREDIS',
    'DEBUG',
    'AD_GROUPS',
    'SESSION_COOKIE_NAME',
    'SESSION_COOKIE_EXPIRES'
];

class RequiredEnvs implements AbstractRequiredEnvs
{
    public function validate(array $envs): void
    {
        $missing = [];

        foreach (REQUIRED_ENVS as $key) {
            if (
                !array_key_exists($key, $envs) ||
                $envs[$key] === '' ||
                $envs[$key] === null
            ) {
                $missing[] = $key;
            }
        }

        if (!empty($missing)) {
            throw new MissingEnvKeysException($missing);
        }
    }
}
