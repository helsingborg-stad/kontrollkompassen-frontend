<?php

declare(strict_types=1);

use \KoKoP\Models\PropertyRegistrationHistory;
use PHPUnit\Framework\TestCase;

final class PropertyRegistrationHistoryTest extends TestCase
{
    private PropertyRegistrationHistory $history;

    protected function setUp(): void
    {;

        $this->history = new PropertyRegistrationHistory((object) [
            "registrationDate" => "20010101",
            "countyCode" => "countyCode_value",
            "municipalityCode" => "municipalityCode_value",
            "parishCode" => "parishCode_value",
            "property" => (object) [
                "designation" => "designation_value",
                "key" => "key_value",
            ],
            "type" => (object) [
                "code" => "code_value",
                "description" => "description_value",
            ]
        ]);
    }

    public function testReturnsRegistrationDateSuccessfully(): void
    {
        $this->assertSame($this->history->getRegistrationDate(), "2001-01-01");
    }
    public function testReturnsCountyCodeSuccessfully(): void
    {
        $this->assertSame($this->history->getCountyCode(), "countyCode_value");
    }
    public function testReturnsMunicipalityCodeSuccessfully(): void
    {
        $this->assertSame($this->history->getMunicipalityCode(), "municipalityCode_value");
    }
    public function testReturnsParishCodeSuccessfully(): void
    {
        $this->assertSame($this->history->getParishCode(), "parishCode_value");
    }
    public function testReturnsPropertyDesignationSuccessfully(): void
    {
        $this->assertSame($this->history->getPropertyDesignation(), "designation_value");
    }
    public function testReturnsPropertyKeySuccessfully(): void
    {
        $this->assertSame($this->history->getPropertyKey(), "key_value");
    }
    public function testReturnsTypeCodeSuccessfully(): void
    {
        $this->assertSame($this->history->getTypeCode(), "code_value");
    }
    public function testReturnsTypeDescriptionSuccessfully(): void
    {
        $this->assertSame($this->history->getTypeDescription(), "description_value");
    }

    public function testReturnsDefaultValuesSuccessfully(): void
    {
        $history = new PropertyRegistrationHistory((object) []);

        $this->assertEmpty($history->getRegistrationDate());
        $this->assertEmpty($history->getCountyCode());
        $this->assertEmpty($history->getMunicipalityCode());
        $this->assertEmpty($history->getParishCode());
        $this->assertEmpty($history->getPropertyDesignation());
        $this->assertEmpty($history->getPropertyKey());
        $this->assertEmpty($history->getTypeCode());
        $this->assertEmpty($history->getTypeDescription());
    }
    public function testSerializeJsonCorrectly(): void
    {
        $this->assertSame(json_encode($this->history), '{"registrationDate":"20010101","countyCode":"countyCode_value","municipalityCode":"municipalityCode_value","parishCode":"parishCode_value","property":{"designation":"designation_value","key":"key_value"},"type":{"code":"code_value","description":"description_value"}}');
    }
}
