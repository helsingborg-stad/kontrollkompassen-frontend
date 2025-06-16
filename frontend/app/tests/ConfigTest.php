<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \KoKoP\Helper\Config;

final class ConfigTest extends TestCase
{
    private $config;

    protected function setUp(): void
    {
        $this->config = new Config(array(
            "ENCRYPT_VECTOR" => "ABCDEFGHIJKLMNOP",
            "TEST_KEY_1" => "ABCDEF"
        ));
    }
    public function testReturnsValueOfKnownKeySuccessfully(): void
    {
        // Make sure the values are equals
        $this->assertEquals($this->config->getValue("ENCRYPT_VECTOR"), "ABCDEFGHIJKLMNOP");
    }
    public function testReturnsNullForUnknownKey(): void
    {
        // Make sure the values are equals
        $this->assertEquals($this->config->getValue("TEST_KEY_1"), null);
    }
    public function testReturnsDefaultForUndefinedKey(): void
    {
        // Make sure the values are equals
        $this->assertEquals($this->config->getValue("TEST_KEY_1", "DEFAULT"), "DEFAULT");
    }
    public function testReturnsArrays(): void
    {
        $config = new Config(array(
            "AD_GROUPS" => ["a", "b"],
        ));

        // Make sure the values are equals
        $this->assertEquals($config->getValue("AD_GROUPS"), ["a", "b"]);
    }

    public function testConfigHasDefaultValues(): void
    {
        $config = new Config();

        $this->assertEquals(
            $config->getValues(),
            array(
                'API_KEY' => false,
                'API_URL' => false,
                'MS_AUTH' => false,
                'ENCRYPT_VECTOR' => false,
                'ENCRYPT_KEY' => false,
                'ENCRYPT_CIPHER' => false,
                'PREDIS' => false,
                'DEBUG' => false,
                'AD_GROUPS' => false,
                'SESSION_COOKIE_NAME' => false,
                'SESSION_COOKIE_EXPIRES' => false,
            )
        );
    }
}
