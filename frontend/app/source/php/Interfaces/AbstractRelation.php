<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractRelation extends AbstractDeregistration
{
    public function getIdentityNumber(): string;
    public function getTypeCode(): string;
    public function getTypeDescription(): string;
    public function getCustodyDate(): string;
    public function isDeregistered(): bool;
}
