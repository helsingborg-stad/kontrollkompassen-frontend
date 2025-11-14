<?php

declare(strict_types=1);

namespace KoKoP\Services;

use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Helper\DotEnvLoader;
use \KoKoP\Helper\ConfigFactory;

class ServicesFactory
{
    public static function create(): AbstractServices
    {
        return new RuntimeServices(
            new ConfigFactory(new DotEnvLoader(BASEPATH . '../'))->create()
        );
    }
}
