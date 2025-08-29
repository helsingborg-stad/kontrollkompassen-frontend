<?php

declare(strict_types=1);

namespace KoKoP\Services;

use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Helper\Config;

function getConfig(): array
{
    $configPath = __DIR__ . '/../../../../config.json';

    return file_exists($configPath)
        ? json_decode(file_get_contents($configPath), true)
        : [];
}

class ServicesFactory
{
    public static function createFromEnv(): AbstractServices
    {
        $config = new Config(getConfig());

        return $config->getValue('MS_AUTH', false)
            ? new NoAuthRuntimeServices($config)
            : new RuntimeServices($config);
    }
}
