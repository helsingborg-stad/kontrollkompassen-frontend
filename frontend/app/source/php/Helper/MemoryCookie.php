<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractCookie;
use \KoKoP\Helper\Cookie;

/**
 * Wrapper class to allow cookie management during tests
 */
class MemoryCookie extends Cookie implements AbstractCookie
{
    public function __construct()
    {
        $this->server = [
            "SERVER_NAME" => "Memory",
            "HTTPS" => false
        ];
        $this->cookie = array();
        $this->setcookie = function (string $key, mixed $data, mixed $options): bool {
            if ($options["expires"] === -1) {
                unset($this->cookie[$key]);
            } else {
                $this->cookie[$key] = $data;
            }
            return true;
        };
    }
}
