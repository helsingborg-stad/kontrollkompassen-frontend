<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractOrganization
{
    public function getFile(string $orgNo, AbstractUser $user): AbstractFile;
}
