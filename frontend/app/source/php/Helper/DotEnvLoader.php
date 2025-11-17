<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \Dotenv\Dotenv;
use \KoKoP\Interfaces\AbstractEnvLoader;

class DotEnvLoader implements AbstractEnvLoader
{
    public function __construct(
        private string $path,
        private ?string $filename = null
    ) {}

    public function load(): array
    {
        Dotenv::createImmutable(
            $this->path,
            $this->_normalizeFilename()
        )->load();

        return $_ENV;
    }

    private function _normalizeFilename(): string
    {
        $f = empty($this->filename) ? '.env' : $this->filename;
        if (!file_exists($this->path . $f)) {
            throw new \RuntimeException("Environment file not found: " . $this->path . $f, 500);
        }
        return $f;
    }
}
