<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractPerson
{
    public function isDeregistered(): bool;
    public function getDeregistrationCode(): string;
    public function getDeregistrationReason(): string;
    public function getDeregistrationDate(): string;
    public function getGivenName(): string;
    public function getFamilyName(): string;
    public function getAdditionalName(): string;
    public function getAddressLocality(): string;
    public function getPostalCode(): string;
    public function getStreetAddress(): string;
    public function getProvinceCode(): string;
    public function getMunicipalityCode(): string;
    public function jsonSerialize(): mixed;
}
