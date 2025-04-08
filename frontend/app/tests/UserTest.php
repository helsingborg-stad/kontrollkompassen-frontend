<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \KoKoP\Models\User;
use \KoKoP\Helper\Config;
use \KoKoP\Interfaces\AbstractConfig;

final class UserTest extends TestCase
{
    protected $user;
    protected $config;

    protected function setUp(): void
    {
        $this->user = new User(null, (object) [
            "samaccountname" => "samaccountname_value",
            "memberof" => "A=a,A=b,B=b,C=c,D=d",
            "sn" => "sn_value",
            "displayname" => "displayname_value",
            "company" => "company_value",
            "mail" => "mail_value",
        ]);
    }
    public function testReturnsAccountNameSuccessfully(): void
    {
        $this->assertEquals($this->user->getAccountName(), "samaccountname_value");
    }
    public function testReturnsLastNameSuccessfully(): void
    {
        $this->assertEquals($this->user->getLastName(), "sn_value");
    }
    public function testReturnsDisplayNameSuccessfully(): void
    {
        $this->assertEquals($this->user->getDisplayName(), "displayname_value");
    }
    public function testReturnsCompanyNameSuccessfully(): void
    {
        $this->assertEquals($this->user->getCompanyName(), "company_value");
    }
    public function testReturnsMailAddressSuccessfully(): void
    {
        $this->assertEquals($this->user->getMailAddress(), "mail_value");
    }
    public function testReturnsGroupsSuccessfully(): void
    {
        $this->assertEquals($this->user->getGroups(), [
            "A" => [0 => "a"],
            "b" => [0 => ""],
            "B" => ["b"],
            "C" => ["c"],
            "D" => ["d"],
        ]);
    }
    public function testFormatUserSuccessfully(): void
    {
        $this->assertEquals($this->user->format(), (object)[
            "firstname" => "Displayname_value",
            "lastname" => "Sn_value",
            "administration" => "company_value",
        ]);
    }
    public function testReturnsDefaultValuesSuccessfully(): void
    {
        $user = new User(null);
        $this->assertEmpty($user->getAccountName());
        $this->assertEmpty($user->getLastName());
        $this->assertEmpty($user->getDisplayName());
        $this->assertEmpty($user->getCompanyName());
        $this->assertEmpty($user->getMailAddress());
        $this->assertEmpty($user->getGroups());
    }
    public function testSerializeJsonCorrectly(): void
    {
        $this->assertSame(json_encode($this->user), '{"samaccountname":"samaccountname_value","memberof":"A=a,b,B=b,C=c,D=d","company":"company_value","displayname":"displayname_value","sn":"sn_value","mail":"mail_value"}');
    }
}
