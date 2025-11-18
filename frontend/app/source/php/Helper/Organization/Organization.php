<?php

declare(strict_types=1);

namespace KoKoP\Helper\Organization;

use Psr\Http\Message\ResponseInterface as Response;

use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Interfaces\AbstractOrganization;
use \KoKoP\Interfaces\AbstractUser;
use \KoKoP\Helper\Organization\OrganizationException;
use \KoKoP\Enums\OrganizationErrorReason;
use \KoKoP\Helper\Sanitize;
use \KoKoP\Helper\Stream\FileStream;
use KoKoP\Interfaces\AbstractRequest;

const DEFAULT_FILENAME = 'uppslag.xlsx';
const DEFAULT_CONTENT_TYPE = 'application/octet-stream';

class Organization implements AbstractOrganization
{
    public function __construct(
        private AbstractConfig $config,
        private AbstractRequest $request
    ) {}

    /**
     * @throws OrganizationException
     */
    public function generateDownload(
        Response $response,
        AbstractUser $user,
        int $orgNo,
        mixed $services
    ): Response {
        try {
            $fileStream = new FileStream(
                $this->config->getValue('API_KEY', '123abc'),
                $this->config->getValue('API_URL', ''),
                $this->request
            );

            $responseWithBody = $response
                ->withBody(
                    $fileStream->fetch([
                        'orgNo' => (string) $orgNo,
                        'email' => $user->getMailAddress(),
                        'groups' => $user->getGroups()['CN'],
                        'services' => array_keys($services),
                    ])
                );

            return $responseWithBody
                ->withHeader(
                    'Content-Type',
                    $fileStream->getContentType() ?: DEFAULT_CONTENT_TYPE
                )
                ->withHeader(
                    'Content-Disposition',
                    "attachment; filename*=UTF-8''" . ($fileStream->getFilename() ?: DEFAULT_FILENAME)
                );
        } catch (\TypeError $e) {
            throw new OrganizationException(OrganizationErrorReason::InvalidLenghtSelected, $e);
        } catch (\Exception $e) {
            throw new OrganizationException(OrganizationErrorReason::ServiceError, $e);
        }
    }

    /**
     * @throws OrganizationException
     */
    public function validateOrgNo(mixed $value): int
    {
        if (is_null($value) || $value === '') {
            throw new OrganizationException(OrganizationErrorReason::Empty);
        }

        if (!is_numeric($value)) {
            throw new OrganizationException(OrganizationErrorReason::InvalidFormat);
        }

        $orgNo = Sanitize::number($value);
        $length = strlen((string) $orgNo);

        if ($length < 9 || $length > 13) {
            throw new OrganizationException(OrganizationErrorReason::InvalidLenght);
        }

        return $orgNo;
    }
}
