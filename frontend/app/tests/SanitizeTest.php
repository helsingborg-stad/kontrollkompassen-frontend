<?php

declare(strict_types=1);

use \KoKoP\Helper\Sanitize;
use PHPUnit\Framework\TestCase;

final class SanitizeTest extends TestCase
{
    public function testCanSanitizeNumber(): void
    {
        // Boolean
        $this->assertSame(Sanitize::number('a|1b^2c\n3 '), '123');
    }

    public function testCanSanitizeString(): void
    {
        // Boolean
        $this->assertSame(Sanitize::string(true), '');
        $this->assertSame(Sanitize::string(false), '');
        // Arbitrary
        $this->assertSame(Sanitize::string(null), '');
        // Valid string
        $this->assertSame(Sanitize::string('string'), 'string');
    }
    public function testCanSanitizePassword(): void
    {
        // Escape slash
        $this->assertSame(Sanitize::password('pwd/pwd'), 'pwd\/pwd');
        // Remove backslash
        $this->assertSame(Sanitize::password('pwd\pwd'), 'pwdpwd');
    }
}
