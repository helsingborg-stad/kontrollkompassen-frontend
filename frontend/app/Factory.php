<?php

declare(strict_types=1);

use \KoKoP\Interfaces\AbstractApp;
use \KoKoP\App;
use \KoKoP\AppSlim;
use \KoKoP\Services\RuntimeServices;

function getConfig(string $path): array
{
    return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
}

function createAppFromConfig(string $configFilePath): AbstractApp
{
    $config = getConfig($configFilePath);

    if ($config['APP_CLASS'] === 'slim') {
        return new AppSlim(new RuntimeServices($config));
    }

    return new App(new RuntimeServices($config));
}
