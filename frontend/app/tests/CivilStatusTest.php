<?php

declare(strict_types=1);

use \KoKoP\Models\CivilStatus;
use PHPUnit\Framework\TestCase;

final class CivilStatusTest extends TestCase
{
    private CivilStatus $status;

    protected function setUp(): void
    {;
        $this->status = new CivilStatus((object) [
            "code" => "code_value",
            "description" => "description_value",
            "date" => "20010101"
        ]);
    }

    public function testReturnsCivilStatusCodeSuccessfully(): void
    {
        $this->assertSame($this->status->getCivilStatusCode(), "code_value");
    }
    public function testReturnsCivilStatusDescriptionSuccessfully(): void
    {
        $this->assertSame($this->status->getCivilStatusDescription(), "description_value");
    }
    public function testReturnsCivilStatusDateSuccessfully(): void
    {
        $this->assertSame($this->status->getCivilStatusDate(), "2001-01-01");
    }
    public function testReturnsDefaultValuesSuccessfully(): void
    {
        $status = new CivilStatus((object) []);

        $this->assertEmpty($status->getCivilStatusCode());
        $this->assertEmpty($status->getCivilStatusDescription());
        $this->assertEmpty($status->getCivilStatusDate());
    }
    public function testSerializeJsonCorrectly(): void
    {
        $this->assertSame(json_encode($this->status), '{"code":"code_value","description":"description_value","date":"20010101"}');
    }
}
