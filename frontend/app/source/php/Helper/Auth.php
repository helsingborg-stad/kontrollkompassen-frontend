<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Enums\AuthErrorReason;
use \KoKoP\Interfaces\AbstractUser;
use \KoKoP\Interfaces\AbstractRequest;
use \KoKoP\Interfaces\AbstractAuth;
use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Helper\AuthException;
use \KoKoP\Models\User;
use stdClass;

class Auth implements AbstractAuth
{
    protected AbstractRequest $request;
    protected string $endpoint;
    protected string|array $allowedGroups;

    public function __construct(private AbstractConfig $config, AbstractRequest $request)
    {
        $this->request = $request;
        $this->endpoint = rtrim($config->getValue('MS_AUTH', ""), "/");
        $this->allowedGroups = $config->getValue('AD_GROUPS', []);
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getAllowedGroups(): string|array
    {
        return $this->allowedGroups;
    }

    public function authenticate(string $name, string $password): AbstractUser
    {
        // Execute request
        $response = $this->request->post($this->endpoint . '/user/current', [
            'username' => $name,
            'password' => Sanitize::password($password)
        ]);
        //Check http response
        if ($response->isErrorResponse()) {
            throw new AuthException(AuthErrorReason::HttpError);
        }
        // Check response data
        $data = $response->getContent()->{0} ?? new stdClass;
        $user = new User($this->config, $data);

        if (strtolower($user->getAccountName()) !== strtolower($name)) {
            throw new AuthException(AuthErrorReason::InvalidCredentials);
        }
        // Check groups
        if (!$this->isAuthorized($user->getGroups())) {
            throw new AuthException(AuthErrorReason::Unauthorized);
        }
        return $user;
    }
    /**
     * Checks if the user is authorized to access the application.
     *
     * Matches if member of contains required key.
     *
     * @return bool The result indicating whether the user is authorized.
     */
    protected function isAuthorized($groups)
    {
        //No group lock defined
        if (empty($this->allowedGroups)) {
            return true;
        }

        if (array_key_exists('CN', $groups) && is_array($this->allowedGroups) && count($this->allowedGroups)) {
            foreach ($this->allowedGroups as $group) {
                if (in_array($group, $groups['CN'])) {
                    return true;
                }
            }
        }
        return false;
    }
}
