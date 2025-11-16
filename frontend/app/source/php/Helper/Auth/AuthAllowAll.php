<?php

declare(strict_types=1);

namespace KoKoP\Helper\Auth;

use \KoKoP\Interfaces\AbstractUser;
use \KoKoP\Interfaces\AbstractAuth;
use \KoKoP\Models\User;

class AuthAllowAll implements AbstractAuth
{
    public function getEndpoint(): string
    {
        return '';
    }

    public function getAllowedGroups(): string|array
    {
        return [];
    }

    public function authenticate(string $name, string $password): AbstractUser
    {
        return new User(null, (object) [
            'samaccountname' => 'dude1234',
            'memberof' => 'CN=KOKOP-DEVELOPERS',
            'company' => 'DudeCorp',
            'displayname' => 'Dude McDuder - Super User',
            'sn' => 'McDuder',
            'mail' => 'Dude.McDuder@example.com',
        ]);
    }
}
