<?php

declare(strict_types=1);

namespace KoKoP\Models;

use JsonSerializable;
use \KoKoP\Helper\Format;
use \KoKoP\Interfaces\AbstractPropertyRegistrationHistory;
use stdClass;

class PropertyRegistrationHistory implements AbstractPropertyRegistrationHistory, JsonSerializable
{
    private string $registrationDate = "";
    private string $countyCode = "";
    private string $municipalityCode = "";
    private string $parishCode = "";
    private string $designation = "";
    private string $key = "";
    private string $code = "";
    private string $description = "";

    public function __construct(object $history)
    {
        if (is_object($history)) {
            // Map from json
            $this->registrationDate = $history->registrationDate ?? "";
            $this->countyCode = $history->countyCode ?? "";
            $this->municipalityCode = $history->municipalityCode ?? "";
            $this->parishCode = $history->parishCode ?? "";
            $property = $history->property ?? new stdClass;
            $this->designation = $property->designation ?? "";
            $this->key = $property->key ?? "";
            $type = $history->type ?? new stdClass;
            $this->code = $type->code ?? "";
            $this->description = $type->description ?? "";
        }
    }

    public function getRegistrationDate(): string
    {
        return Format::date($this->registrationDate);
    }
    public function getCountyCode(): string
    {
        return $this->countyCode;
    }
    public function getMunicipalityCode(): string
    {
        return $this->municipalityCode;
    }
    public function getParishCode(): string
    {
        return $this->parishCode;
    }
    public function getPropertyDesignation(): string
    {
        return $this->designation;
    }
    public function getPropertyKey(): string
    {
        return $this->key;
    }
    public function getTypeCode(): string
    {
        return $this->code;
    }
    public function getTypeDescription(): string
    {
        return $this->description;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "registrationDate" => $this->registrationDate,
            "countyCode" => $this->countyCode,
            "municipalityCode" => $this->municipalityCode,
            "parishCode" => $this->parishCode,
            "property" => [
                "designation" => $this->designation,
                "key" => $this->key,
            ],
            "type" => [
                "code" => $this->code,
                "description" => $this->description,
            ]
        ];
    }
}
