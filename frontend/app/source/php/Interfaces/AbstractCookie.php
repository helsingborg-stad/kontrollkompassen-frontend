<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

/**
 * Wrapper class to allow cookie management during tests
 */
interface AbstractCookie
{
    public function setCookie(string $key, mixed $data = "", int $expires = -1): bool;
    public function getCookie(string $key): string|null;
    public function removeCookie(string $key): bool;
    public function getCookies(): array;
    public function getServerVars(): array;
}
