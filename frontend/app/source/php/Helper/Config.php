<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractConfig;

class Config implements AbstractConfig
{
    public function __construct(private array $_config) {}

    public function getValues(): array
    {
        return $this->_config;
    }

    public function getValue(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = $this->_config;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}
