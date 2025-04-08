<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractPropertyRegistrationHistory
{
    public function getRegistrationDate(): string;
    public function getCountyCode(): string;
    public function getMunicipalityCode(): string;
    public function getParishCode(): string;
    public function getPropertyDesignation(): string;
    public function getPropertyKey(): string;
    public function getTypeCode(): string;
    public function getTypeDescription(): string;
}
