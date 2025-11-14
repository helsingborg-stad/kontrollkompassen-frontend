<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \KoKoP\Helper\Config;

final class ConfigTest extends TestCase
{
    public function testReturnsValueOfKnownKeySuccessfully(): void
    {
        $this->assertEquals(new Config([
            'ENCRYPT_VECTOR' => 'ABCDEFGHIJKLMNOP',
        ])->getValue('ENCRYPT_VECTOR'), 'ABCDEFGHIJKLMNOP');
    }

    public function testReturnsNullForUnknownKey(): void
    {
        $this->assertEquals(new Config([])->getValue('TEST_KEY_1'), null);
    }

    public function testReturnsDefaultForUndefinedKey(): void
    {
        $this->assertEquals(new Config([])->getValue('TEST_KEY_2', 'DEFAULT'), 'DEFAULT');
    }

    public function testReturnsArrays(): void
    {
        $this->assertEquals(new Config([
            'AD_GROUPS' => ['Group1', 'Group2']
        ])->getValue('AD_GROUPS'), ['Group1', 'Group2']);
    }

    public function testConfigHasDefaultValues(): void
    {
        $this->assertEquals(
            new Config([
                'API_KEY' => null,
                'API_URL' => null,
                'MS_AUTH' => null,
                'ENCRYPT_VECTOR' => null,
                'ENCRYPT_KEY' => null,
                'ENCRYPT_CIPHER' => null,
                'PREDIS' => false,
                'DEBUG' => false,
                'AD_GROUPS' => [],
                'SESSION_COOKIE_NAME' => 'kokop_session',
                'SESSION_COOKIE_EXPIRES' => 3600,
            ])->getValues(),
            [
                'API_KEY' => null,
                'API_URL' => null,
                'MS_AUTH' => null,
                'ENCRYPT_VECTOR' => null,
                'ENCRYPT_KEY' => null,
                'ENCRYPT_CIPHER' => null,
                'PREDIS' => false,
                'DEBUG' => false,
                'AD_GROUPS' => [],
                'SESSION_COOKIE_NAME' => 'kokop_session',
                'SESSION_COOKIE_EXPIRES' => 3600,
            ]
        );
    }
}
