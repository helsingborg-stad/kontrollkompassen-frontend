<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractUser
{
    public function getAccountName(): string;
    public function getGroups(): array;
    public function getCompanyName(): string;
    public function getDisplayName(): string;
    public function getLastName(): string;
    public function getMailAddress(): string;
    public function format(): object;
    public function jsonSerialize(): mixed;
}
