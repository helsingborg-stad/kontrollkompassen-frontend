<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractSecure
{
    public function encrypt(mixed $data): string|false;
    public function decrypt(string $data): mixed;
    public function getEncryptVector(): string;
    public function getEncryptCipher(): string;
    public function getEncryptKey(): string;
}
