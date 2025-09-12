<?php

namespace KoKoP\Enums;

enum OrganizationErrorReason: int
{
    case InvalidLenght = 0;
    case InvalidFormat = 1;
    case Empty = 2;
    case ServiceError = 3;

    public function message(): string
    {
        return match ($this) {
            self::InvalidLenght => 'Ogiltig längd för organisationsnummer. Skall vara 10 eller 12 siffror',
            self::InvalidFormat => 'Ogiltigt format för organisationsnummer. Endast siffror är tillåtna',
            self::Empty => 'Fältet: Organisationsnummer får inte vara tomt',
            self::ServiceError => 'Ett fel uppstod vid hämtning av uppslagsfilen. Försök igen senare',
        };
    }

    public function allowedValues(): array
    {
        return [
            'only digits',
            'length 10 or 12',
            'not empty',
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
            self::ServiceError => 500,
        };
    }
}
