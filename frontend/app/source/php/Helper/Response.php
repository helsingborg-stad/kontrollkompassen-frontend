<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractResponse;

class Response implements AbstractResponse
{
    protected int $status;
    protected ?string $hash;
    protected ?object $content;

    public function __construct(int $status, ?string $hash, ?object $content)
    {
        $this->status = $status;
        $this->hash = $hash;
        $this->content = $content;
    }
    public function getStatusCode(): int
    {
        return $this->status;
    }
    public function getHash(): ?string
    {
        return $this->hash;
    }
    public function getContent(): ?object
    {
        return $this->content;
    }
    public function isErrorResponse(): bool
    {
        return $this->status > 400;
    }
}
