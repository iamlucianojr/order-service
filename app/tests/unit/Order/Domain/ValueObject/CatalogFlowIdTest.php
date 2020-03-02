<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\CatalogFlowId;
use App\Shared\ValueObjectInterface;
use PHPUnit\Framework\TestCase;

final class CatalogFlowIdTest extends TestCase
{
    public function testGenerate(): void
    {
        $catalogFlowId = CatalogFlowId::generate();
        $this->assertNotNull($catalogFlowId);
    }

    public function testToString(): void
    {
        $catalogFlowId = CatalogFlowId::generate();
        $this->assertIsString($catalogFlowId->toString());
        $this->assertNotEmpty($catalogFlowId->toString());
    }

    public function testEquals(): void
    {
        $catalogFlowIdOne = CatalogFlowId::generate();
        $catalogFlowIdTwo = clone $catalogFlowIdOne;
        $this->assertTrue($catalogFlowIdOne->equals($catalogFlowIdTwo));
        $this->assertFalse($catalogFlowIdOne->equals(CatalogFlowId::generate()));
    }

    public function testEqualsUsingDifferentClass(): void
    {
        $catalogFlowId = CatalogFlowId::generate();
        $this->assertFalse($catalogFlowId->equals($this->createMock(ValueObjectInterface::class)));
    }

    public function testFromString(): void
    {
        $catalogFlowId = CatalogFlowId::fromString('9fe448d5-8d92-48a0-9585-68dc6ae1e53f');
        $this->assertNotNull($catalogFlowId);
        $this->assertNotEmpty($catalogFlowId);
    }
}
