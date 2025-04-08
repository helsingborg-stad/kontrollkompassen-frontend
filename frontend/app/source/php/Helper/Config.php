<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractConfig;

class Config implements AbstractConfig
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $this->parse($config);
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
    /**
     * Configure the application environment variables.
     *
     * This function retrieves environment variables from the system or uses default values from the provided configuration.
     *
     * @param array $config An associative array containing values for environment variables.
     *
     * @return void
     */
    protected function parse(array $config): array
    {
        //Get env vars
        $env = array(
            'MS_AUTH' => getenv('MS_AUTH'),
            'MS_NAVET' => getenv('MS_NAVET'),
            'MS_NAVET_AUTH' => getenv('MS_NAVET_AUTH'),
            'ENCRYPT_VECTOR' => getenv('ENCRYPT_VECTOR'),
            'ENCRYPT_KEY' => getenv('ENCRYPT_KEY'),
            'ENCRYPT_CIPHER' => getenv('ENCRYPT_CIPHER'),
            'PREDIS' => getenv('PREDIS'),
            'DEBUG' => getenv('DEBUG'),
            'AD_GROUPS' => getenv('AD_GROUPS'),
            'SESSION_COOKIE_NAME' => getenv('SESSION_COOKIE_NAME'),
            'SESSION_COOKIE_EXPIRES' => getenv('SESSION_COOKIE_EXPIRES'),
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
