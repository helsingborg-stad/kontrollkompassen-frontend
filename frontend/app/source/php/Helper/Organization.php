<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use Psr\Http\Message\ResponseInterface as Response;

use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Interfaces\AbstractOrganization;
use \KoKoP\Interfaces\AbstractUser;

const DEFAULT_FILENAME = 'uppslag.xlsx';
const DEFAULT_CONTENT_TYPE = 'application/octet-stream';

class Organization implements AbstractOrganization
{
    public function __construct(private AbstractConfig $config) {}

    public function generateDownload(
        Response $response,
        AbstractUser $user,
        string $orgNo
    ): Response {
        try {
            $fileStream = new FileStream(
                $this->config->getValue('API_KEY', '123abc'),
                $this->config->getValue('API_URL', null)
            );

            $responseWithBody = $response
                ->withBody(
                    $fileStream->fetch([
                        'orgNo' => $orgNo,
                        'email' => $user->getMailAddress(),
                    ])
                );

            $responseWithContentTypeHeader = $responseWithBody
                ->withHeader(
                    'Content-Type',
                    $fileStream->getContentType() ?? DEFAULT_CONTENT_TYPE
                );

            $responseWithContentDispositionHeader = $responseWithContentTypeHeader
                ->withHeader(
                    'Content-Disposition',
                    'attachment; filename*=UTF-8\'\'' . $fileStream->getFilename() ?? DEFAULT_FILENAME
                );

            return $responseWithContentDispositionHeader;
        } catch (\Exception $e) {
            $response
                ->getBody()
                ->write('Error generating download: ' . $e->getMessage());
            return $response->withStatus(500);
        }
    }
}
