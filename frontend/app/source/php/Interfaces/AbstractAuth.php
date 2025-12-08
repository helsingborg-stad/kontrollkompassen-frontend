<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractAuth
{
    public function authenticate(string $name, string $password): AbstractUser;
    public function getEndpoint(): string;
}
