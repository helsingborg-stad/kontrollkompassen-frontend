<?php

declare(strict_types=1);

use \KoKoP\Models\Relation;
use PHPUnit\Framework\TestCase;

final class RelationTest extends TestCase
{
    private Relation $relation;

    protected function setUp(): void
    {;
        $this->relation = new Relation((object) [
            "identityNumber" => "identityNumber_value",
            "custodyDate" => "custodyDate_value",
            "type" => (object) [
                "code" => "code_value",
                "description" => "description_value",
            ],
            "deregistration" => (object) [
                "reasonCode" => "reasonCode_value",
                "reasonDescription" => "reasonDescription_value",
                "deregistrationDate" => "deregistrationDate_value"
            ]
        ]);
    }
    public function testReturnsIdentityNumberSuccessfully(): void
    {
        $this->assertSame($this->relation->getIdentityNumber(), "identityNumber_value");
    }
    public function testReturnsCustodyDateSuccessfully(): void
    {
        $this->assertSame($this->relation->getCustodyDate(), "custodyDate_value");
    }
    public function testReturnsTypeCodeSuccessfully(): void
    {
        $this->assertSame($this->relation->getTypeCode(), "code_value");
    }
    public function testReturnsTypeDescriptionSuccessfully(): void
    {
        $this->assertSame($this->relation->getTypeDescription(), "description_value");
    }
    public function testReturnsReasonCodeSuccessfully(): void
    {
        $this->assertSame($this->relation->getDeregistrationReasonCode(), "reasonCode_value");
    }
    public function testReturnsReasonDescriptionSuccessfully(): void
    {
        $this->assertSame($this->relation->getDeregistrationReasonDescription(), "reasonDescription_value");
    }
    public function testReturnsDeregistrationDateSuccessfully(): void
    {
        $this->assertSame($this->relation->getDeregistrationDate(), "deregistrationDate_value");
    }
    public function testReturnsIsDeregistered(): void
    {
        $relation = new Relation((object) []);
        $this->assertSame($relation->isDeregistered(), false);
    }
    public function testReturnsDefaultValuesSuccessfully(): void
    {
        $relation = new Relation((object) []);

        $this->assertEmpty($relation->getIdentityNumber());
        $this->assertEmpty($relation->getCustodyDate());
        $this->assertEmpty($relation->getTypeCode());
        $this->assertEmpty($relation->getTypeDescription());
        $this->assertEmpty($relation->getDeregistrationReasonCode());
        $this->assertEmpty($relation->getDeregistrationReasonDescription());
        $this->assertEmpty($relation->getDeregistrationDate());
    }
    public function testSerializeJsonCorrectly(): void
    {
        $this->assertSame(json_encode($this->relation), '{"identityNumber":"identityNumber_value","custodyDate":"custodyDate_value","deregistration":{"reasonCode":"reasonCode_value","reasonDescription":"reasonDescription_value","deregistrationDate":"deregistrationDate_value"},"type":{"code":"code_value","description":"description_value"}}');
    }
}
