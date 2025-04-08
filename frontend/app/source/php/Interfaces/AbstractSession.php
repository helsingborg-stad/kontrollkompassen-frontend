<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractSession
{
    public function getUser(): AbstractUser|false;
    public function setSession(AbstractUser $user): bool;
    public function endSession(): void;
    public function isValidSession(): bool;
    public function getSessionName(): string;
    public function getSessionExpiration(): int;
}
