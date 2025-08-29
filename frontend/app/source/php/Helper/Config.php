<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractConfig;

class Config implements AbstractConfig
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $this->_parse($config);
    }

    public function getValues(): array
    {
        return $this->config;
    }

    public function getValue(string $key, mixed $default = null): mixed
    {
        return isset($this->config[$key]) &&
            $this->config[$key] !== false ? $this->config[$key] : $default;
    }

    protected function _parse(array $config): array
    {
        //Get env vars
        $env = array(
            'MS_AUTH' => getenv('MS_AUTH'),
            'ENCRYPT_VECTOR' => getenv('ENCRYPT_VECTOR'),
            'ENCRYPT_KEY' => getenv('ENCRYPT_KEY'),
            'ENCRYPT_CIPHER' => getenv('ENCRYPT_CIPHER'),
            'PREDIS' => getenv('PREDIS'),
            'DEBUG' => getenv('DEBUG'),
            'AD_GROUPS' => getenv('AD_GROUPS'),
            'SESSION_COOKIE_NAME' => getenv('SESSION_COOKIE_NAME'),
            'SESSION_COOKIE_EXPIRES' => getenv('SESSION_COOKIE_EXPIRES'),
            'API_URL' => getenv('API_URL'),
            'API_KEY' => getenv('API_KEY'),
        );

        //Fallback to default
        foreach ($env as $key => $item) {
            if ($item === false) {
                if (isset($config[$key]) && is_object($config[$key])) {
                    $config[$key] = (array) $config[$key];
                }
                $env[$key] = $config[$key] ?? false;
            }
        }
        return $env;
    }
}
