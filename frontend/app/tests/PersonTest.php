<?php

declare(strict_types=1);

use \KoKoP\Models\Person;
use PHPUnit\Framework\TestCase;

final class PersonTest extends TestCase
{
    private Person $person;

    protected function setUp(): void
    {;

        $this->person = new Person((object)  [
            "deregistrationCode" => "deregistrationCode_value",
            "deregistrationDate" => "20010101",
            "deregistrationReason" => "deregistrationReason_value",
            "givenName" => "givenName_value",
            "familyName" => "familyName_value",
            "additionalName" => "additionalName_value",
            "address" => (object) [
                "addressLocality" => "addressLocality_value",
                "postalCode" => "25050",
                "streetAddress" => "streetAddress_value",
                "provinceCode" => "provinceCode_value",
                "municipalityCode" => "municipalityCode_value",
            ]
        ]);
    }

    public function testReturnsDeregistrationCodeSuccessfully(): void
    {
        $this->assertSame($this->person->getDeregistrationCode(), "deregistrationCode_value");
    }
    public function testReturnsDeregistrationDateSuccessfully(): void
    {
        $this->assertSame($this->person->getDeregistrationDate(), "2001-01-01");
    }
    public function testReturnsDeregistrationReasonSuccessfully(): void
    {
        $this->assertSame($this->person->getDeregistrationReason(), "deregistrationReason_value");
    }
    public function testReturnsGivenNameSuccessfully(): void
    {
        $this->assertSame($this->person->getGivenName(), "givenName_value");
    }
    public function testReturnsFamilyNameSuccessfully(): void
    {
        $this->assertSame($this->person->getFamilyName(), "familyName_value");
    }
    public function testReturnsAdditionalNameSuccessfully(): void
    {
        $this->assertSame($this->person->getAdditionalName(), "additionalName_value");
    }
    public function testReturnsAddressLocalitySuccessfully(): void
    {
        $this->assertSame($this->person->getAddressLocality(), "Addresslocality_Value");
    }
    public function testReturnsPostalCodeSuccessfully(): void
    {
        $this->assertSame($this->person->getPostalCode(), "250 50");
    }
    public function testReturnsStreetAddressSuccessfully(): void
    {
        $this->assertSame($this->person->getStreetAddress(), "Streetaddress_Value");
    }
    public function testReturnsProvinceCodeSuccessfully(): void
    {
        $this->assertSame($this->person->getProvinceCode(), "provinceCode_value");
    }
    public function testReturnsMunicipalityCodeSuccessfully(): void
    {
        $this->assertSame($this->person->getMunicipalityCode(), "municipalityCode_value");
    }
    public function testReturnsIsDeregistered(): void
    {
        $this->assertSame($this->person->isDeregistered(), true);
    }

    public function testReturnsDefaultValuesSuccessfully(): void
    {
        $person = new Person((object) []);

        $this->assertEmpty($person->getDeregistrationCode());
        $this->assertEmpty($person->getDeregistrationDate());
        $this->assertEmpty($person->getDeregistrationReason());
        $this->assertEmpty($person->getGivenName());
        $this->assertEmpty($person->getFamilyName());
        $this->assertEmpty($person->getAdditionalName());
        $this->assertEmpty($person->getAddressLocality());
        $this->assertEmpty($person->getPostalCode());
        $this->assertEmpty($person->getStreetAddress());
        $this->assertEmpty($person->getProvinceCode());
        $this->assertEmpty($person->getMunicipalityCode());
    }
    public function testReturnsDeregisteredSuccessfully(): void
    {
        $person = new Person((object) ["deregistrationCode" => "BB"]);

        $this->assertSame($person->isDeregistered(), true);
    }
    public function testSerializeJsonCorrectly(): void
    {
        $this->assertSame(json_encode($this->person), '{"deregistrationCode":"deregistrationCode_value","deregistrationDate":"20010101","deregistrationReason":"deregistrationReason_value","givenName":"givenName_value","familyName":"familyName_value","additionalName":"additionalName_value","address":{"addressLocality":"addressLocality_value","postalCode":"25050","streetAddress":"streetAddress_value","provinceCode":"provinceCode_value","municipalityCode":"municipalityCode_value"}}');
    }
}
