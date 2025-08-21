<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use function \KoKoP\Utils\encodeRFC7230;

final class encodeRFC7230Test extends TestCase
{
    public function testCan_encode_RFC7230(): void
    {
        $this->assertSame(
            'Kålltorps_Kök_AB_123',
            encodeRFC7230('Kålltorps_Kök_AB_123')
        );
    }

    public function testCan_encode_RFC7230_with_special_characters(): void
    {
        $this->assertSame(
            'K%C3%A5lltorps_K%C3%B6k_AB_123',
            encodeRFC7230('K%C3%A5lltorps_K%C3%B6k_AB_123')
        );
    }

    public function testCan_encode_RFC7230_with_empty_string(): void
    {
        $this->assertSame(
            '',
            encodeRFC7230('')
        );
    }
}
