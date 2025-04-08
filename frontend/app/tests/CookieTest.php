<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \KoKoP\Helper\MemoryCookie;

final class CookieTest extends TestCase
{
    const name = 'dummy';

    public function testReturnsNullWhenNotSet(): void
    {
        $cookie = new MemoryCookie();
        // Make sure the values are equals
        $this->assertEquals($cookie->getCookie(self::name), null);
    }
    public function testReturnsValueWhenSetWithOptions(): void
    {
        $cookie = new MemoryCookie();
        $cookie->setCookie(self::name, 'data', 100);

        // Make sure the values are equals
        $this->assertEquals($cookie->getCookie(self::name), 'data');
    }
    public function testRemovesCookieWhenDeleted(): void
    {
        $cookie = new MemoryCookie();
        $cookie->setCookie(self::name, 'data', 100);
        $cookie->removeCookie(self::name);

        // Make sure the values are equals
        $this->assertEquals($cookie->getCookie(self::name), null);
    }
    public function testDoesNothingWhenRemoving(): void
    {
        $cookie = new MemoryCookie();
        $cookie->removeCookie(self::name);

        // Make sure the values are equals
        $this->assertEquals($cookie->getCookie(self::name), null);
    }
    public function testReturnsServerNameOption(): void
    {
        $cookie = new MemoryCookie();
        $server = $cookie->getServerVars();

        // Make sure the values are equals
        $this->assertEquals($server["SERVER_NAME"], "Memory");
    }
    public function testReturnsHttpsOption(): void
    {
        $cookie = new MemoryCookie();
        $server = $cookie->getServerVars();

        // Make sure the values are equals
        $this->assertEquals($server["HTTPS"], false);
    }
}
