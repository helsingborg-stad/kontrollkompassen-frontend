<?php

declare(strict_types=1);

use \KoKoP\Interfaces\AbstractApp;
use \KoKoP\App;
use \KoKoP\AppSlim;
use \KoKoP\ViewSlim;
use \KoKoP\Services\RuntimeServices;
use \KoKoP\View;

function getConfig(string $path): array
{
    return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
}

function createAppFromConfig(string $configFilePath): AbstractApp
{
    $config = getConfig($configFilePath);

    return $config['APP_CLASS'] === 'slim'
        ? new AppSlim(new ViewSlim(new RuntimeServices($config)))
        : new App(new View(new RuntimeServices($config)));
}
