<?php

declare(strict_types=1);

namespace KoKoP\Services;

use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Helper\DotEnvLoader;
use \KoKoP\Helper\ConfigFactory;
use \KoKoP\Helper\MissingEnvKeysException;
use KoKoP\Helper\RequiredEnvs;

const REQUIRED_ENVS = [
    'API_URL',
    'API_KEY',
    'MS_AUTH',
    'ENCRYPT_VECTOR',
    'ENCRYPT_KEY',
    'ENCRYPT_CIPHER',
    'PREDIS',
    'DEBUG',
    'AD_GROUPS',
    'SESSION_COOKIE_NAME',
    'SESSION_COOKIE_EXPIRES'
];

class ServicesFactory
{
    public static function create(): AbstractServices
    {
        try {
            return new RuntimeServices(
                new ConfigFactory(
                    new DotEnvLoader(BASE_PATH . '../'),
                    new RequiredEnvs(REQUIRED_ENVS)
                )->create()
            );
        } catch (MissingEnvKeysException $e) {
            echo $e->getMessage();
            exit(1);
        }
    }
}
