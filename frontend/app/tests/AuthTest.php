<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use NavetSearch\Helper\Auth;
use NavetSearch\Enums\AuthErrorReason;
use NavetSearch\Helper\Config;
use NavetSearch\Helper\Request;
use NavetSearch\Helper\Response;
use NavetSearch\Interfaces\AbstractRequest;

final class AuthTest extends TestCase
{
    private $data;

    protected function setUp(): void
    {
        $this->data = (object) array((object)[
            "sn" => "sn",
            "title" => "title",
            "postalcode" => "postalcode",
            "physicaldeliveryofficename" => "physicaldeliveryofficename",
            "displayname" => "displayname",
            "memberof" => "CN=cn,OU=ou,DC=dc",
            "department" => "department",
            "company" => "company",
            "streetaddress" => "streetaddress",
            "useraccountcontrol" => "useraccountcontrol",
            "lastlogon" => "lastlogon",
            "primarygroupid" => "primarygroupid",
            "samaccountname" => "samaccountname",
            "userprincipalname" => "userprincipalname",
            "mail" => "mail",
            "dn" => "dn"
        ]);
    }
    public function testSuccessfullAuthentication(): void
    {
        $mock = $this->createConfiguredMock(
            AbstractRequest::class,
            [
                'get' => new Response(200, null, null),
                'post' => new Response(200, null, $this->data),
            ],
        );
        // Set auhtorized groups
        $config = new Config(array("AD_GROUPS" => ["cn"]));

        // Create auth module
        $auth = new Auth($config, $mock);

        // Try authenticate
        $user = $auth->authenticate("samaccountname", "samaccountname");

        // Make sure the values are equals
        $this->assertEquals($user->getAccountName(), "samaccountname");
    }
    public function testUnauthorizedException(): void
    {
        $mock = $this->createConfiguredMock(
            AbstractRequest::class,
            [
                'get' => new Response(200, null, null),
                'post' => new Response(200, null, $this->data),
            ],
        );
        // Set auhtorized groups
        $config = new Config(array("AD_GROUPS" => ["unknown"]));

        // Create auth module
        $auth = new Auth($config, $mock);

        // Try authenticate
        $error = (int) AuthErrorReason::Unauthorized;
        $this->expectException("NavetSearch\Helper\AuthException");
        $this->expectExceptionCode($error);

        $auth->authenticate("samaccountname", "samaccountname");
    }
    public function testInvalidCredentialsException(): void
    {
        $mock = $this->createConfiguredMock(
            AbstractRequest::class,
            [
                'get' => new Response(200, null, null),
                'post' => new Response(200, null, new stdClass()),
            ],
        );
        // Set auhtorized groups
        $config = new Config(array());

        // Create auth module
        $auth = new Auth($config, $mock);

        // Try authenticate
        $error = (int) AuthErrorReason::InvalidCredentials;
        $this->expectException("NavetSearch\Helper\AuthException");
        $this->expectExceptionCode($error);

        $auth->authenticate("samaccountname", "samaccountname");
    }
    public function testHttpErrorException(): void
    {
        $mock = $this->createConfiguredMock(
            AbstractRequest::class,
            [
                'get' => new Response(200, null, null),
                'post' => new Response(401, null, null),
            ],
        );
        // Set auhtorized groups
        $config = new Config(array());

        // Create auth module
        $auth = new Auth($config, $mock);

        // Try authenticate
        $error = (int) AuthErrorReason::HttpError;
        $this->expectException("NavetSearch\Helper\AuthException");
        $this->expectExceptionCode($error);

        $auth->authenticate("samaccountname", "samaccountname");
    }
    public function testConfigValuesAreRespected(): void
    {
        $config = new Config(array(
            "MS_AUTH" => "MS_AUTH_VALUE/",
            "AD_GROUPS" => "AD_GROUPS_VALUE"
        ));
        $auth = new Auth($config, new Request());

        $this->assertEquals($auth->getEndpoint(), "MS_AUTH_VALUE");
        $this->assertEquals($auth->getAllowedGroups(), "AD_GROUPS_VALUE");
    }
    public function testConfigHasDefaultValues(): void
    {
        $config = new Config(array());
        $auth = new Auth($config, new Request());

        $this->assertEquals($auth->getEndpoint(), "");
        $this->assertEquals($auth->getAllowedGroups(), []);
    }
}
