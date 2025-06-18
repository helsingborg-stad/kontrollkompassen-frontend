<?php

declare(strict_types=1);

namespace KoKoP\Services;

use KoKoP\Interfaces\AbstractServices;

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
        $config = getConfig();

        return $config['MS_AUTH']
            ? new RuntimeServices($config)
            : new NoAuthRuntimeServices($config);
    }
}
