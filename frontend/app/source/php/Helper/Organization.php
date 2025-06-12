<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Interfaces\AbstractFile;
use \KoKoP\Interfaces\AbstractOrganization;
use \KoKoP\Interfaces\AbstractUser;

class Organization implements AbstractOrganization
{
    protected AbstractConfig $config;
    protected AbstractUser $user;

    public function __construct(AbstractConfig $config)
    {
        $this->config = $config;
    }

    public function getFile(string $orgNo, AbstractUser $user): AbstractFile
    {
        return new File(
            $this->config,
            ['orgNo' => $orgNo, 'user' => $user]
        );
    }
}
