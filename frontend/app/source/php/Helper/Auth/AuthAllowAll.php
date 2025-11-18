<?php

declare(strict_types=1);

namespace KoKoP\Helper\Auth;

use \KoKoP\Enums\AuthErrorReason;
use \KoKoP\Interfaces\AbstractUser;
use \KoKoP\Interfaces\AbstractAuth;
use \KoKoP\Models\User;

class AuthAllowAll implements AbstractAuth
{
    public function getEndpoint(): string
    {
        return '';
    }

    public function getAllowedGroups(): string | array
    {
        return ['DEVELOPER'];
    }

    public function authenticate(string $name, string $password): AbstractUser
    {
        $user = new User(null, (object) [
            'samaccountname' => 'dude1234',
            'memberof' => 'CN=' . $this->getAllowedGroups()[0],
            'company' => 'DudeCorp',
            'displayname' => 'Dude McDuder - Super User',
            'sn' => 'McDuder',
            'mail' => 'Dude.McDuder@example.com',
        ]);

        if (!$this->isAuthorized($user->getGroups())) {
            throw new AuthException(AuthErrorReason::Unauthorized);
        }

        return $user;
    }

    protected function isAuthorized($groups): bool
    {
        if (array_key_exists('CN', $groups) && is_array($this->getAllowedGroups()) && count($this->getAllowedGroups())) {
            foreach ($this->getAllowedGroups() as $g) {
                if (in_array($g, $groups['CN'])) {
                    return true;
                }
            }
        }

        return false;
    }
}
