<?php

declare(strict_types=1);

use \KoKoP\Helper\Validate;
use PHPUnit\Framework\TestCase;

final class ValidateTest extends TestCase
{
    public function testCanValidatePnr(): void
    {
        // 12-digits
        $this->assertSame(Validate::pnr('123456789012'), true);
        $this->assertSame(Validate::pnr('1a23b456c78-9012'), true);
        // Less digits
        $this->assertSame(Validate::pnr('123456789'), false);
        // More digits
        $this->assertSame(Validate::pnr('123456789012345'), false);
    }
    public function testCanValidateUsername(): void
    {
        // Asserted format: 4-char + 4-digits
        $this->assertSame(Validate::username('abcd1234'), true);
        $this->assertSame(Validate::username('abc'), false);
        $this->assertSame(Validate::username('123'), false);
        $this->assertSame(Validate::username('abc123'), false);
    }
}
