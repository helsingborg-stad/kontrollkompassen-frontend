<?php

declare(strict_types=1);

namespace KoKoP\Services;

use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Helper\DotEnvLoader;
use \KoKoP\Helper\ConfigFactory;
use \KoKoP\Helper\RequiredEnvs;
use \KoKoP\Helper\MissingEnvKeysException;

class ServicesFactory
{
    public static function create(): AbstractServices
    {
        try {
            return new RuntimeServices(
                ConfigFactory::create(
                    new DotEnvLoader(BASE_PATH . '../'),
                    new RequiredEnvs()
                )
            );
        } catch (MissingEnvKeysException $e) {
            echo $e->getMessage();
            exit(1);
        }
    }
}
