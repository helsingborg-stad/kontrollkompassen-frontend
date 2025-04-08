<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \KoKoP\Helper\MemoryCache;
use \KoKoP\Helper\Config;
use \KoKoP\Helper\Secure;
use \KoKoP\Helper\Response;

final class MemoryCacheTest extends TestCase
{
    public function testCanGetAndSetValueWithoutEncryption(): void
    {
        $value = array(
            "data1" => 10,
            "data2" => "value"
        );

        $cache = new MemoryCache(null);

        $cache->set(new Response(200, 'hash', (object) $value));
        $item = $cache->get('hash');

        // Make sure the values are equals
        $this->assertEquals((array) $item, $value);
    }
    public function testCanGetAndSetValueWithEncryption(): void
    {
        $value = array(
            "data1" => 10,
            "data2" => "value"
        );
        $config = new Config(array(
            "ENCRYPT_VECTOR" => "ABCDEFGHIJKLMNOP",
            "ENCRYPT_KEY" => "ABCDEFGHIJ"
        ));

        $cache = new MemoryCache(new Secure($config));

        $cache->set(new Response(200, 'hash', (object) $value));
        $item = $cache->get('hash');

        // Make sure the values are equals
        $this->assertEquals((array)$item, $value);
    }
}
