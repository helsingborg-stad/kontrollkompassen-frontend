<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractLink;
use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Interfaces\AbstractResponse;
use stdClass;

/**
 * Wrapper class to allow download of 
 */
class Link implements AbstractLink
{
    protected ?object $data;
    protected string $baseUrl;

    public function __construct(AbstractConfig $config, AbstractResponse $response)
    {
        $this->baseUrl = $config->getValue('BACKEND_BASE_URL', 'http://localhost:8000');
        $this->data = $response->getContent() ?? new stdClass;
    }

    public function getDownloadUrl(): string
    {
        return $this->baseUrl . $this->data->downloadUrl;
    }

    public function getFileName(): string
    {
        return $this->data->name ?? '';
    }

    public function getFileSize(): int
    {
        return $this->data->size ?? -1;
    }
}
