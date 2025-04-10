<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractSecure;
use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Interfaces\AbstractSession;
use \KoKoP\Interfaces\AbstractCookie;
use \KoKoP\Interfaces\AbstractUser;
use \KoKoP\Models\User;

class Session implements AbstractSession
{
    protected string $name;
    protected string $expires;
    protected AbstractSecure $secure;
    protected AbstractCookie $cookie;

    public function __construct(private AbstractConfig $config, AbstractSecure $secure, AbstractCookie $cookie)
    {
        $this->name = $config->getValue(
            'SESSION_COOKIE_NAME',
            "kokop_auth_cookie"
        );
        $this->expires = (string) $config->getValue(
            'SESSION_COOKIE_EXPIRES',
            (string) 60 * 60 * 10
        );

        $this->secure = $secure;
        $this->cookie = $cookie;
    }

    public function getSessionName(): string
    {
        return $this->name;
    }

    public function getSessionExpiration(): int
    {
        return (int) $this->expires;
    }

    public function setSession(AbstractUser $user): bool
    {
        return $this->cookie->setCookie(
            $this->name,
            $this->secure->encrypt($user),
            (time() + (int) $this->expires)
        );
    }

    public function isValidSession(): bool
    {
        return (bool) $this->getUser();
    }

    public function getUser(): AbstractUser|false
    {
        $value = $this->cookie->getCookie($this->name);

        if (isset($value)) {
            return new User($this->config, (object) $this->secure->decrypt($value));
        }
        return false;
    }

    public function endSession(): void
    {
        $this->cookie->removeCookie($this->name);
    }
}
