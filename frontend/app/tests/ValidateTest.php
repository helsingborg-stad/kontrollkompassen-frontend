<?php

declare(strict_types=1);

use \KoKoP\Helper\Validate;
use PHPUnit\Framework\TestCase;

final class ValidateTest extends TestCase
{
    public function testCanValidateUsername(): void
    {
        // Asserted format: 4-char + 4-digits
        $this->assertSame(Validate::username('abcd1234'), true);
        $this->assertSame(Validate::username('abc'), false);
        $this->assertSame(Validate::username('123'), false);
        $this->assertSame(Validate::username('abc123'), false);
    }
}
