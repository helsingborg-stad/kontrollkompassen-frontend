<?php

declare(strict_types=1);

use \KoKoP\Helper\Format;
use PHPUnit\Framework\TestCase;

final class FormatTest extends TestCase
{
    public function testCanFormatSocialSecurityNumber(): void
    {
        // Too short
        $this->assertSame(Format::socialSecuriyNumber('12345678901'), false);
        // Too long
        $this->assertSame(Format::socialSecuriyNumber('1234567890123'), false);
        // Correct
        $this->assertSame(Format::socialSecuriyNumber('123456789012'), '12345678-9012');
    }
    public function testCanFormatSex(): void
    {
        // Male
        $this->assertSame(Format::sex('123456789012'), 'M');
        $this->assertSame(Format::sex('123456789032'), 'M');
        // Female
        $this->assertSame(Format::sex('123456789002'), 'F');
        $this->assertSame(Format::sex('123456789022'), 'F');
        $this->assertSame(Format::sex('123456789042'), 'F');
    }
    public function testCanFormatMunicipalityCode(): void
    {
        // Predefined
        $this->assertSame(Format::municipalityCode('25'), 'Helsingborg (25)');
        $this->assertSame(Format::municipalityCode('84'), 'Höganäs (84)');
        // Others
        $this->assertSame(Format::municipalityCode('44'), '44');
    }
    public function testCanFormatUnicodeTitle(): void
    {
        // Predefined
        $this->assertSame(Format::capitalize("åran är ö"), 'Åran Är Ö');
    }
}
