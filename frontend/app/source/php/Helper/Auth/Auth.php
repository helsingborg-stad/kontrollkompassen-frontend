<?php

declare(strict_types=1);

namespace KoKoP\Helper\Auth;

use \KoKoP\Enums\AuthErrorReason;
use \KoKoP\Interfaces\AbstractUser;
use \KoKoP\Interfaces\AbstractRequest;
use \KoKoP\Interfaces\AbstractAuth;
use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Helper\Auth\AuthException;
use \KoKoP\Helper\Sanitize;
use \KoKoP\Models\User;
use stdClass;

class Auth implements AbstractAuth
{
    private AbstractConfig $config;
    protected AbstractRequest $request;
    protected string $endpoint;

    public function __construct(AbstractConfig $config, AbstractRequest $request)
    {
        $this->config = $config;
        $this->request = $request;
        $this->endpoint = $config->getValue('MS_AUTH', '');
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function authenticate(string $name, string $password): AbstractUser
    {
        $response = $this->request->post($this->endpoint . '/user/current', [
            'username' => $name,
            'password' => Sanitize::password($password)
        ]);

        if ($response->isErrorResponse()) {
            throw new AuthException(AuthErrorReason::HttpError);
        }

        $user = new User($this->config, $response->getContent()->{0} ?? new stdClass);

        if (strtolower($user->getAccountName()) !== strtolower($name)) {
            throw new AuthException(AuthErrorReason::InvalidCredentials);
        }

        $allowPublicAccess = $this->config->getValue('ALLOW_PUBLIC_ACCESS', false);
        if ($allowPublicAccess === false && empty($user->getGroups())) {
            throw new AuthException(AuthErrorReason::Unauthorized);
        }

        return $user;
    }
}
