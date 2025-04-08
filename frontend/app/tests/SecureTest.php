<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \KoKoP\Helper\Secure;
use \KoKoP\Helper\Config;

final class SecureTest extends TestCase
{
    private Secure $secure;

    protected function setUp(): void
    {
        $this->secure = new Secure(new Config());
    }
    public function testReturnObjectWhenDecryptAndEncryptArray(): void
    {
        $value = array(
            "data1" => 10,
            "data2" => "value"
        );
        $encrypt = $this->secure->encrypt($value);
        $decrypt = $this->secure->decrypt($encrypt);

        // Make sure the values are equals
        $this->assertEquals((array)$decrypt, $value);
    }
    public function testReturnNullWhenDecryptAndEncryptString(): void
    {
        $encrypt = $this->secure->encrypt("data");
        $decrypt = $this->secure->decrypt($encrypt);

        // Make sure the values are equals
        $this->assertEquals($decrypt, null);
    }
    public function testReturnObjectWhenDecryptAndEncryptObject(): void
    {
        $value = (object) array(
            "data1" => 10,
            "data2" => "value"
        );
        $encrypt = $this->secure->encrypt($value);
        $decrypt = $this->secure->decrypt($encrypt);

        // Make sure the values are equals
        $this->assertEquals($decrypt, $value);
    }
    public function testConfigValuesAreRespected(): void
    {
        $config = new Config(array(
            "ENCRYPT_CIPHER" => "ENCRYPT_CIPHER_VALUE",
            "ENCRYPT_VECTOR" => "ENCRYPT_VECTOR_VALUE",
            "ENCRYPT_KEY" => "ENCRYPT_KEY_VALUE",
        ));
        $session = new Secure($config);

        $this->assertEquals($session->getEncryptCipher(), "ENCRYPT_CIPHER_VALUE");
        $this->assertEquals($session->getEncryptVector(), "ENCRYPT_VECTOR_VALUE");
        $this->assertEquals($session->getEncryptKey(), "ENCRYPT_KEY_VALUE");
    }
    public function testConfigHasDefaultValues(): void
    {
        $config = new Config();
        $session = new Secure($config);

        $this->assertEquals($session->getEncryptCipher(), "AES-128-CTR");
        $this->assertEquals($session->getEncryptVector(), "ABCDEFGHIJKLMNOP");
        $this->assertEquals($session->getEncryptKey(), "ABCDEFGHIJ");
    }
}
