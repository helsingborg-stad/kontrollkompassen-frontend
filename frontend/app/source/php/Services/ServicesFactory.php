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
    public static function create(): AbstractServices
    {
        return new RuntimeServices(new Config(getConfig()));
    }
}
