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

    public function authenticate(string $name, string $password): AbstractUser
    {
        $user = new User(null, (object) [
            'samaccountname' => 'dude1234',
            'memberof' => 'CN=DEVELOPER',
            'company' => 'DudeCorp',
            'displayname' => 'Dude McDuder - Super User',
            'sn' => 'McDuder',
            'mail' => 'Dude.McDuder@example.com',
        ]);

        return $user;
    }
}
