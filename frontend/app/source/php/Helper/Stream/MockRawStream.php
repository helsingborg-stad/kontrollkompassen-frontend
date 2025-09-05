<?php

declare(strict_types=1);

namespace KoKoP\Helper\Stream;

use Psr\Http\Message\StreamInterface;

class MockRawStream implements StreamInterface
{
    protected $content;
    protected int $position = 0;

    public function __construct()
    {
        $this->content = 'Hello World! This is a mock stream for testing purposes.';
    }

    public function __toString(): string
    {
        return (string) $this->content;
    }

    public function close(): void
    {
        // No resources to close
    }

    public function detach()
    {
        return null;
    }

    public function getSize(): ?int
    {
        return strlen($this->content);
    }

    public function tell(): int
    {
        return $this->position;
    }

    public function eof(): bool
    {
        return $this->position >= strlen($this->content);
    }

    public function isSeekable(): bool
    {
        return true;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        if (!$this->isSeekable()) {
            throw new \RuntimeException('Stream is not seekable');
        }

        switch ($whence) {
            case SEEK_SET:
                $this->position = $offset;
                break;
            case SEEK_CUR:
                $this->position += $offset;
                break;
            case SEEK_END:
                $this->position = strlen($this->content) + $offset;
                break;
            default:
                throw new \InvalidArgumentException('Invalid seek operation');
        }

        if ($this->position < 0) {
            throw new \RuntimeException('Position cannot be negative');
        }
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write($string): int
    {
        throw new \RuntimeException('Stream is not writable');
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read($length): string
    {
        $result = substr($this->content, $this->position, $length);
        $this->position += strlen($result);
        return $result;
    }

    public function getContents(): string
    {
        $result = substr($this->content, $this->position);
        $this->position = strlen($this->content);
        return $result;
    }

    public function getMetadata($key = null): string | array | null
    {
        $metadata = [
            'wrapper_data' => [
                'content-type' => 'plain/text; charset=utf-8',
                'content-disposition' => "attachment; filename*=UTF-8''K%25C3%25A4lltorps_Pl%25C3%25A5t_%2526_Svets_AB",
            ],
            'seekable' => $this->isSeekable(),
        ];

        if ($key === null) {
            return $metadata;
        }

        return $metadata[$key] ?? null;
    }
}
