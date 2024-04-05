<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use NavetSearch\Helper\Config;
use NavetSearch\Helper\MemoryCookie;
use NavetSearch\Helper\Secure;
use NavetSearch\Helper\Session;
use NavetSearch\Helper\User;

final class SessionTest extends TestCase
{
    protected $session;

    protected function setUp(): void
    {
        $config = new Config();
        $secure = new Secure($config);

        $this->session = new Session($config, $secure, new MemoryCookie());
    }
    public function testReturnsInvalidSession(): void
    {
        $this->assertEquals($this->session->isValidSession(), false);
    }
    public function testReturnsValidSession(): void
    {
        $user = new User((object) [
            "samaccountname" => "hardy"
        ]);
        $this->session->setSession($user);
        $this->assertEquals($this->session->isValidSession(), true);
    }
    public function testConfigValuesAreRespected(): void
    {
        $config = new Config(array(
            "SESSION_COOKIE_NAME" => "SessionName",
            "SESSION_COOKIE_EXPIRES" => "100"
        ));
        $session = new Session($config, new Secure($config), new MemoryCookie());

        $this->assertEquals($session->getSessionName(), "SessionName");
        $this->assertEquals($session->getSessionExpiration(), 100);
    }
    public function testConfigHasDefaultValues(): void
    {
        $config = new Config();
        $session = new Session($config, new Secure($config), new MemoryCookie());

        $this->assertEquals($session->getSessionName(), "navet_auth_cookie");
        $this->assertEquals($session->getSessionExpiration(), 36000);
    }
}
