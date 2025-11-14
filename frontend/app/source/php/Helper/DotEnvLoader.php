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
        $dotenv = $this->filename
            ? Dotenv::createImmutable($this->path, $this->filename)
            : Dotenv::createImmutable($this->path);

        $dotenv->load();

        return $_ENV;
    }
}
