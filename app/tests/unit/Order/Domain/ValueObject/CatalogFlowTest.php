<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\CatalogFlow;
use App\Order\Domain\ValueObject\CatalogFlowId;
use App\Shared\ValueObjectInterface;
use PHPUnit\Framework\TestCase;

final class CatalogFlowTest extends TestCase
{
    public function testFromArray(): void
    {
        $catalogFlow = CatalogFlow::fromArray([
            'uuid' => '10d853df-15e2-460e-9892-da62361c116e',
            'version' => 3,
        ]);

        $this->assertNotEmpty($catalogFlow);
    }

    public function testToArray(): void
    {
        $data = [
            'uuid' => 'd6c4bf4f-cfe4-41d2-bfaa-178f160dfadf',
            'version' => 4,
        ];
        $catalogFlow = CatalogFlow::fromArray($data);

        $this->assertEquals($data, $catalogFlow->toArray());
    }

    public function testEquals(): void
    {
        $catalogFlowOne = CatalogFlow::fromArray(['uuid' => CatalogFlowId::generate()->toString(), 'version' => 2]);
        $catalogFlowTwo = CatalogFlow::fromArray(['uuid' => CatalogFlowId::generate()->toString(), 'version' => 5]);
        $this->assertFalse($catalogFlowOne->equals($catalogFlowTwo));

        $catalogFlowOneClone = clone $catalogFlowOne;
        $this->assertTrue($catalogFlowOne->equals($catalogFlowOneClone));
    }

    public function testEqualsWithDifferentClass(): void
    {
        $tableIdentifier = CatalogFlow::fromArray(['uuid' => CatalogFlowId::generate()->toString(), 'version' => 3]);
        $this->assertFalse($tableIdentifier->equals($this->createMock(ValueObjectInterface::class)));
    }

    public function testToString(): void
    {
        $data = [
            'uuid' => '867c8060-22a7-4e9f-b9e5-2e835f3f264a',
            'version' => 3,
        ];
        $catalogFlow = CatalogFlow::fromArray($data);

        $this->assertIsString($catalogFlow->__toString());
    }

    public function testVersion(): void
    {
        $data = [
            'uuid' => '8b6c41cf-3a73-4a9c-a2c4-362490d7f193',
            'version' => 9,
        ];
        $catalogFlow = CatalogFlow::fromArray($data);

        $this->assertIsNumeric($catalogFlow->version());
        $this->assertEquals(9, $catalogFlow->version());
    }

    public function testCatalogFlowId(): void
    {
        $data = [
            'uuid' => '8506407e-671f-405c-9d76-3e742c73b70d',
            'version' => 30,
        ];
        $catalogFlow = CatalogFlow::fromArray($data);

        $this->assertEquals(CatalogFlowId::fromString($data['uuid']), $catalogFlow->catalogFlowId());
    }
}
