<?php

namespace KoKoP\Enums;

enum OrganizationErrorReason: int
{
    case InvalidLength = 0;
    case InvalidFormat = 1;
    case Empty = 2;
    case ServiceError = 3;
    case InvalidLengthSelected = 4;
    case NotNumericFormat = 5;

    public function message(): string
    {
        return match ($this) {
            self::InvalidLength => 'Ogiltig längd för organisationsnummer. Skall vara 10 eller 12 siffror',
            self::InvalidFormat => 'Ogiltigt format för organisationsnummer. (112233-4455 eller 1122334455)',
            self::Empty => 'Fältet: Organisationsnummer får inte vara tomt',
            self::ServiceError => 'Ett fel uppstod vid hämtning av uppslagsfilen. Försök igen senare',
            self::InvalidLengthSelected => 'Du måste välja minst en datakälla för att kunna söka på organisationsnummer',
            self::NotNumericFormat => 'Ogiltigt format för organisationsnummer. Endast siffror är tillåtna',
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
            self::InvalidLength, self::InvalidFormat, self::Empty, self::InvalidLengthSelected, self::NotNumericFormat => 400,
            self::ServiceError => 500
        };
    }
}
