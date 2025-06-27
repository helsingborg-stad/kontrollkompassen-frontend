<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Stream;

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
            $fileStream = new FileStream($this->config)
                ->getStream([
                    'orgNo' => $orgNo,
                    'email' => $user->getMailAddress(),
                ]);

            $responseWithBody = $response->withBody($fileStream);
            return $responseWithBody
                ->withHeader(
                    'Content-Type',
                    getContentTypeFromStream($fileStream) ?? DEFAULT_CONTENT_TYPE
                )
                ->withHeader(
                    'Content-Disposition',
                    'attachment; filename="' . getFilenameFromStream($fileStream) ?? DEFAULT_FILENAME . '"'
                );
        } catch (\Exception $e) {
            $response
                ->getBody()
                ->write('Error generating download: ' . $e->getMessage());
            return $response->withStatus(500);
        }
    }
}

function getFilenameFromStream(Stream $s): string | bool
{
    $contentDisposition = current(
        array_filter(
            $s->getMetadata('wrapper_data'),
            fn($item) => str_starts_with($item, 'content-disposition')
        )
    );

    if (str_contains($contentDisposition, 'filename*=')) {
        preg_match(
            "/filename\\*=(?:UTF-8'')?([^;\\r\\n]+)/i",
            $contentDisposition,
            $matches
        );

        return isset($matches[1])
            ? rawurldecode($matches[1])
            : false;
    }

    preg_match(
        '/filename="([^"]+)"/',
        $contentDisposition,
        $matches
    );

    return $matches[1] ?? false;
}

function getContentTypeFromStream(Stream $s): string | bool
{
    $value = current(
        array_filter(
            $s->getMetadata('wrapper_data'),
            fn($item) => str_starts_with($item, 'content-type')
        )
    );

    return $value !== false && str_contains($value, ':')
        ? explode(':', $value)[1]
        : false;
}
