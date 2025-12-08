<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractSecure;
use \KoKoP\Interfaces\AbstractConfig;

/**
 * Class Secure
 *
 * Provides methods for encrypting and decrypting data using AES-128-CTR encryption.
 * The encryption key and initialization vector are currently set as static variables,
 * but it is recommended to replace them with environment variables in a production environment.
 *
 * @package KoKop\Helper
 */
class Secure implements AbstractSecure
{
    protected string $cipher;
    protected string $vector;
    protected string $key;

    public function __construct(AbstractConfig $config)
    {
        $this->cipher = $config->getValue(
            'ENCRYPT_CIPHER',
            'AES-128-CTR'
        );
        $this->vector = $config->getValue(
            'ENCRYPT_VECTOR',
            'ABCDEFGHIJKLMNOP'
        );
        $this->key = $config->getValue(
            'ENCRYPT_KEY',
            'ABCDEFGHIJ'
        );
    }

    public function getEncryptVector(): string
    {
        return $this->vector;
    }
    public function getEncryptCipher(): string
    {
        return $this->cipher;
    }
    public function getEncryptKey(): string
    {
        return $this->key;
    }

    public function encrypt(mixed $data): string|false
    {
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }
        return openssl_encrypt($data, $this->cipher, $this->key, 0, $this->vector);
    }

    public function decrypt($encryptedData): mixed
    {
        $decrypted = openssl_decrypt($encryptedData, $this->cipher, $this->key, 0, $this->vector);
        if (is_string($decrypted)) {
            $decrypted = json_decode($decrypted);
        }
        return $decrypted;
    }
}
