<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractLink;
use \KoKoP\Interfaces\AbstractResponse;
use stdClass;

/**
 * Wrapper class to allow download of 
 */
class Link implements AbstractLink
{
    protected ?object $data;

    public function __construct(AbstractResponse $response)
    {
        $this->data =  $response->getContent()->{0} ?? new stdClass;
    }
    public function getDownloadUrl(): string
    {
        return $this->data->url;
    }
    public function getFileName(): ?string
    {
        return $this->data->name ?? '';
    }
    public function getFileSize(): int
    {
        return $this->data->size ?? -1;
    }
}
