<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use Closure;
use \KoKoP\Interfaces\AbstractCookie;

/**
 * Wrapper class to allow cookie management during tests
 */
class Cookie implements AbstractCookie
{
    protected Closure $setcookie;
    protected array $server;
    protected array $cookie;

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->cookie = $_COOKIE;
        $this->setcookie = function (string $key, mixed $data, mixed $options): bool {
            return setcookie($key, $data, $options);
        };
    }
    protected function getOptions(int $expires): mixed
    {
        return [
            'expires' => $expires,
            'path' => '/',
            'domain' => $this->server['SERVER_NAME'],
            'secure' => isset($this->server['HTTPS']) ? true : false,
            'httponly' => false,
            'samesite' => 'None'
        ];
    }
    public function setCookie(string $key, mixed $data = "", int $expires = -1): bool
    {
        return ($this->setcookie)(
            $key,
            $data,
            $this->getOptions($expires)
        );
    }
    public function getCookie(string $key): string|null
    {
        return isset($this->cookie[$key]) ? $this->cookie[$key] : null;
    }
    public function removeCookie(string $key): bool
    {
        return ($this->setcookie)(
            $key,
            "",
            $this->getOptions(-1)
        );
    }
    public function getCookies(): array
    {
        return $this->cookie;
    }
    public function getServerVars(): array
    {
        return $this->server;
    }
}
