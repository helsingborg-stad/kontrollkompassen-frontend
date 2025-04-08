<?php

declare(strict_types=1);

namespace KoKoP\Models;

use JsonSerializable;
use \KoKoP\Helper\Format;
use \KoKoP\Interfaces\AbstractCivilStatus;

class CivilStatus implements AbstractCivilStatus, JsonSerializable
{
    private string $code = "";
    private string $description = "";
    private string $date = "";

    public function __construct(object $status)
    {
        if (is_object($status)) {
            // Map from json
            $this->code = $status->code ?? "";
            $this->description = $status->description ?? "";
            $this->date = $status->date ?? "";
        }
    }

    public function getCivilStatusCode(): string
    {
        return $this->code;
    }
    public function getCivilStatusDescription(): string
    {
        return $this->description;
    }
    public function getCivilStatusDate(): string
    {
        return Format::date($this->date);
    }
    public function jsonSerialize(): mixed
    {
        return [
            "code" => $this->code,
            "description" => $this->description,
            "date" => $this->date,
        ];
    }
}
