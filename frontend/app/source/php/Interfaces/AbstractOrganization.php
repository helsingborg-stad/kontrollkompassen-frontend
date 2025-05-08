<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractOrganization
{
    public function getLink(string $orgNo, AbstractUser $user): AbstractLink;
}
