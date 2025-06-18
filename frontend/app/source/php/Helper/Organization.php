<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Stream;

use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Interfaces\AbstractOrganization;
use \KoKoP\Interfaces\AbstractUser;

class Organization implements AbstractOrganization
{
    public function __construct(private AbstractConfig $config) {}

    public function generateDownload(
        Response $response,
        AbstractUser $user,
        string $orgNo
    ): Response {
        try {
            $fileStream = new FileStream($this->config)
                ->getStream([
                    'orgNo' => $orgNo,
                    'email' => $user->getMailAddress(),
                ]);

            $responseWithBody = $response->withBody($fileStream);
            return $responseWithBody
                ->withHeader('Content-Type', getContentTypeFromStream($fileStream))
                ->withHeader('Content-Disposition', 'attachment; filename="' . getFilenameFromStream($fileStream) . '"');
        } catch (\Exception $e) {
            $response
                ->getBody()
                ->write('Error generating download: ' . $e->getMessage());
            return $response->withStatus(500);
        }
    }
}

function getFilenameFromStream(Stream $s): string
{
    $value = current(array_filter($s->getMetadata('wrapper_data'), fn($item) => str_starts_with($item, 'content-disposition')));
    preg_match('/filename="([^"]+)"/', $value, $matches);
    return $matches[1] ?? 'export.xlsx';
}

function getContentTypeFromStream(Stream $s): string
{
    $value = current(array_filter($s->getMetadata('wrapper_data'), fn($item) => str_starts_with($item, 'content-type')));
    return explode(':', $value)[1] ?? 'application/octet-stream';
}
