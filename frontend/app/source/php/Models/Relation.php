<?php

declare(strict_types=1);

namespace KoKoP\Models;

use JsonSerializable;
use \KoKoP\Interfaces\AbstractRelation;
use \KoKoP\Interfaces\AbstractDeregistration;
use stdClass;

class Relation implements AbstractRelation, AbstractDeregistration, JsonSerializable
{
    private string $identityNumber = "";
    private string $code = "";
    private string $description = "";
    private string $custodyDate = "";
    private string $reasonCode = "";
    private string $reasonDescription = "";
    private string $deregistrationDate = "";

    public function __construct(object $relation)
    {
        if (is_object($relation)) {
            // Map from json
            $this->identityNumber = $relation->identityNumber ?? "";
            $this->custodyDate = $relation->custodyDate ?? "";
            $type = $relation->type ?? new stdClass;
            $this->code = $type->code ?? "";
            $this->description = $type->description ?? "";
            $deregistration = $relation->deregistration ?? new stdClass;
            $this->reasonCode = $deregistration->reasonCode ?? "";
            $this->reasonDescription = $deregistration->reasonDescription ?? "";
            $this->deregistrationDate = $deregistration->deregistrationDate ?? "";
        }
    }
    public function isDeregistered(): bool
    {
        return !empty($this->reasonCode);
    }

    public function getIdentityNumber(): string
    {
        return $this->identityNumber;
    }
    public function getTypeCode(): string
    {
        return $this->code;
    }
    public function getTypeDescription(): string
    {
        return $this->description;
    }
    public function getCustodyDate(): string
    {
        return $this->custodyDate;
    }
    public function getDeregistrationReasonCode(): string
    {
        return $this->reasonCode;
    }
    public function getDeregistrationReasonDescription(): string
    {
        return $this->reasonDescription;
    }
    public function getDeregistrationDate(): string
    {
        return $this->deregistrationDate;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "identityNumber" => $this->identityNumber,
            "custodyDate" => $this->custodyDate,
            "deregistration" => [
                "reasonCode" => $this->reasonCode,
                "reasonDescription" => $this->reasonDescription,
                "deregistrationDate" => $this->deregistrationDate,
            ],
            "type" => (object) [
                "code" => $this->code,
                "description" => $this->description,
            ]
        ];
    }
}
