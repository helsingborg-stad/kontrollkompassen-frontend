<?php

declare(strict_types=1);

namespace KoKoP\Helper\Auth;

use KoKoP\Interfaces\AbstractRequest;
use KoKoP\Interfaces\AbstractAuth;
use KoKoP\Interfaces\AbstractConfig;
use KoKoP\Helper\Auth\AuthAllowAll;
use KoKoP\Helper\Auth\Auth;

class AuthFactory
{
    public static function create(
        AbstractConfig $config,
        AbstractRequest $request
    ): AbstractAuth {
        return $config->getValue('MS_AUTH', '')
            ? new Auth($config, $request)
            : new AuthAllowAll();
    }
}
