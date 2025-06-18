<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

use Psr\Http\Message\ResponseInterface as Response;

interface AbstractOrganization
{
    public function generateDownload(
        Response $response,
        AbstractUser $user,
        string $orgNo
    ): Response;
}
