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
        // Read config
        $this->name = $config->getValue(
            'SESSION_COOKIE_NAME',
            "kokop_auth_cookie"
        );
        $this->expires = (string) $config->getValue(
            'SESSION_COOKIE_EXPIRES',
            (string) 60 * 60 * 10
        );
        // Encryption/Decryption
        $this->secure = $secure;
        // Cookie management
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

    /**
     * Sets the authentication cookie with encrypted user data.
     *
     * @param mixed $data The user data to be encrypted and stored in the authentication cookie.
     * @return bool Returns true if the cookie is successfully set, false otherwise.
     */
    public function setSession(AbstractUser $user): bool
    {
        return $this->cookie->setCookie(
            $this->name,
            $this->secure->encrypt($user),
            (time() + (int) $this->expires)
        );
    }

    /**
     * Checks if the user is authenticated based on the presence of the authentication cookie.
     *
     * @return bool Returns true if the user is authenticated, false otherwise.
     */
    public function isValidSession(): bool
    {
        return (bool) $this->getUser();
    }

    /**
     * Retrieves user data from the authentication cookie.
     *
     * @return mixed|false The decrypted user data if the authentication cookie is present, false otherwise.
     */
    public function getUser(): AbstractUser|false
    {
        $value = $this->cookie->getCookie($this->name);

        if (isset($value)) {
            return new User($this->config, (object) $this->secure->decrypt($value));
        }
        return false;
    }

    /**
     * Logs out the user by deleting the authentication cookie.
     */
    public function endSession(): void
    {
        $this->cookie->removeCookie($this->name);
    }
}
