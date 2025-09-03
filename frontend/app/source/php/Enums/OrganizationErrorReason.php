<?php

namespace KoKoP\Enums;

enum OrganizationErrorReason: int
{
    case InvalidLenght = 0;
    case InvalidFormat = 1;
    case Empty = 2;

    public function message(): string
    {
        return match ($this) {
            self::InvalidLenght => 'Ogiltig längd för organisationsnummer. Skall vara mellan 10 och 12 siffror',
            self::InvalidFormat => 'Ogiltigt format för organisationsnummer. Endast siffror är tillåtna',
            self::Empty => 'Organisationsnummer får inte vara tomt',
        };
    }

    public function allowedValues(): array
    {
        return [
            'only digits',
            'length 10 or 12',
        ];
    }

    public function getField(): string
    {
        return 'orgno';
    }

    public function httpErrorCode(): int
    {
        return match ($this) {
            self::InvalidLenght, self::InvalidFormat, self::Empty => 400,
        };
    }
}
