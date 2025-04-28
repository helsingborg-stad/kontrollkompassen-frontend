<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \KoKoP\Helper\Config;
use KoKoP\Helper\Organization;
use \KoKoP\Helper\Response;
use \KoKoP\Interfaces\AbstractRequest;
use KoKoP\Interfaces\AbstractUser;

final class OrganizationTest extends TestCase
{
    protected function setUp(): void {}
    public function testGetLink(): void
    {
        $request = $this->createConfiguredMock(
            AbstractRequest::class,
            [
                'get' => new Response(200, null, null),
                'post' => new Response(200, null,  (object) array((object)[
                    "url" => "url",
                    "name" => "name",
                    "size" => 12
                ])),
            ],
        );
        $user = $this->createConfiguredMock(
            AbstractUser::class,
            [
                'getAccountName' => '',
                'getGroups' => [],
                'getCompanyName' => '',
                'getDisplayName' => '',
                'getLastName' => '',
                'getMailAddress' => '',
                'format' => new stdClass,
                'jsonSerialize' => new stdClass
            ],
        );

        // Set auhtorized groups
        $config = new Config([]);

        // Create Organization module
        $org = new Organization($config, $request);

        $link = $org->getLink('123456789', $user);

        // Make sure the values are equals
        $this->assertEquals($link->getDownloadUrl(), "url");
        $this->assertEquals($link->getFileName(), "name");
        $this->assertEquals($link->getFileSize(), 12);
    }
}
