<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractUser;
use \KoKoP\Interfaces\AbstractAuth;
use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Models\User;
use \KoKoP\Helper\AuthException;
use \KoKoP\Enums\AuthErrorReason;

class AuthAllowAll implements AbstractAuth
{
    private AbstractConfig $config;

    public function __construct(AbstractConfig $config)
    {
        $this->config = $config;
    }

    public function getEndpoint(): string
    {
        return rtrim($this->config->getValue('MS_AUTH', ''), '/');
    }

    public function getAllowedGroups(): string|array
    {
        return $this->config->getValue('AD_GROUPS', []);
    }

    public function authenticate(string $name, string $password): AbstractUser
    {
        $superUser = new User($this->config, (object) [
            'samaccountname' => 'dude1234',
            'memberof' => 'CN=HBGADMR-SLFKontrollkompassen-AvanceradX',
            'company' => 'DudeCorp',
            'displayname' => 'Dude McDuder - Super User',
            'sn' => 'McDuder',
            'mail' => 'Dude.McDuder@example.com',
        ]);

        if (!$this->isAuthorized($superUser->getGroups())) {
            throw new AuthException(AuthErrorReason::Unauthorized);
        }

        return $superUser;
    }

    protected function isAuthorized(array $groups): bool
    {
        $allowedGroups = $this->getAllowedGroups();
        if (empty($allowedGroups)) {
            return true;
        }

        if (array_key_exists('CN', $groups) && is_array($allowedGroups) && count($allowedGroups)) {
            foreach ($allowedGroups as $group) {
                if (in_array($group, $groups['CN'])) {
                    return true;
                }
            }
        }

        return false;
    }
}
