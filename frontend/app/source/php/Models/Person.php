<?php

declare(strict_types=1);

namespace KoKoP\Models;

use JsonSerializable;
use \KoKoP\Helper\Format;
use \KoKoP\Interfaces\AbstractPerson;
use stdClass;

class Person implements AbstractPerson, JsonSerializable
{
    private string $deregistrationCode = "";
    private string $deregistrationReason = "";
    private string $deregistrationDate = "";
    private string $givenName = "";
    private string $familyName = "";
    private string $additionalName = "";
    private string $addressLocality = "";
    private string $postalCode = "";
    private string $streetAddress = "";
    private string $provinceCode = "";
    private string $municipalityCode = "";

    public function __construct(object $person)
    {
        if (is_object($person)) {
            // Map from json
            $this->deregistrationCode = $person->deregistrationCode ?? "";
            $this->deregistrationReason = $person->deregistrationReason ?? "";
            $this->deregistrationDate = $person->deregistrationDate ?? "";
            $this->givenName = $person->givenName ?? "";
            $this->familyName = $person->familyName ?? "";
            $this->additionalName = $person->additionalName ?? "";
            $address = $person->address ?? new stdClass;
            $this->addressLocality = $address->addressLocality ?? "";
            $this->postalCode = $address->postalCode ?? "";
            $this->streetAddress = $address->streetAddress ?? "";
            $this->provinceCode = $address->provinceCode ?? "";
            $this->municipalityCode = $address->municipalityCode ?? "";
        }
    }
    public function isDeregistered(): bool
    {
        if (!empty($this->deregistrationCode)) {
            return true;
        }
        return false;
    }
    public function getDeregistrationCode(): string
    {
        return $this->deregistrationCode;
    }
    public function getDeregistrationReason(): string
    {
        return $this->deregistrationReason;
    }
    public function getDeregistrationDate(): string
    {
        return Format::date($this->deregistrationDate);
    }
    public function getGivenName(): string
    {
        return $this->givenName;
    }
    public function getFamilyName(): string
    {
        return $this->familyName;
    }
    public function getAdditionalName(): string
    {
        return $this->additionalName;
    }
    public function getAddressLocality(): string
    {
        return Format::capitalize($this->addressLocality);
    }
    public function getPostalCode(): string
    {
        return Format::postalCode($this->postalCode);
    }
    public function getStreetAddress(): string
    {
        return Format::capitalize($this->streetAddress);
    }
    public function getProvinceCode(): string
    {
        return $this->provinceCode;
    }
    public function getMunicipalityCode(): string
    {
        return $this->municipalityCode;
    }
    public function jsonSerialize(): mixed
    {
        return [
            "deregistrationCode" => $this->deregistrationCode,
            "deregistrationDate" => $this->deregistrationDate,
            "deregistrationReason" => $this->deregistrationReason,
            "givenName" => $this->givenName,
            "familyName" => $this->familyName,
            "additionalName" => $this->additionalName,
            "address" => [
                "addressLocality" => $this->addressLocality,
                "postalCode" => $this->postalCode,
                "streetAddress" => $this->streetAddress,
                "provinceCode" => $this->provinceCode,
                "municipalityCode" => $this->municipalityCode,
            ]
        ];
    }
}
