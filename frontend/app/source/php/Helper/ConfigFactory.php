<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use KoKoP\Interfaces\AbstractConfig;
use KoKoP\Interfaces\AbstractEnvLoader;
use KoKoP\Interfaces\AbstractRequiredEnvs;

class ConfigFactory
{
    public function __construct(
        private AbstractEnvLoader $_envLoader,
        private AbstractRequiredEnvs $_requiredEnvs
    ) {}

    public function create(): AbstractConfig
    {
        $env = $this->_envLoader->load();

        $this->_requiredEnvs->validate($env);

        $normalized = [];

        foreach ($env as $key => $value) {
            if (is_string($value) && $this->_isJson($value)) {
                $normalized[$key] = json_decode($value, true);
                continue;
            }

            if (is_string($value) && str_contains($value, ',')) {
                $normalized[$key] = array_map('trim', explode(',', $value));
                continue;
            }

            $normalized[$key] = $value;
        }

        return new Config($normalized);
    }

    private function _isJson(string $s): bool
    {
        json_decode($s);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
