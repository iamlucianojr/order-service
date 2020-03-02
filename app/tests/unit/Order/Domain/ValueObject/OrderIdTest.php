<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\OrderId;
use App\Shared\ValueObjectInterface;
use PHPUnit\Framework\TestCase;

final class OrderIdTest extends TestCase
{
    public function testFromString(): void
    {
        $orderId = OrderId::fromString('ca8bfd01-b180-49bf-a956-c9463460040c');
        $this->assertNotNull($orderId);
        $this->assertNotEmpty($orderId);
    }

    public function testToString(): void
    {
        $orderId = OrderId::generate();
        $this->assertIsString($orderId->toString());
    }

    public function testGenerate(): void
    {
        $orderId = OrderId::generate();
        $this->assertNotNull($orderId);
        $this->assertNotEmpty($orderId);
    }

    public function testEquals(): void
    {
        $orderId = OrderId::fromString('b10d5daf-d37a-4d92-8658-3bef08298d86');
        $orderIdTwo = OrderId::fromString('b10d5daf-d37a-4d92-8658-3bef08298d86');
        $this->assertTrue($orderId->equals($orderIdTwo));
    }

    public function testEqualsWithDifferentClass(): void
    {
        $orderId = OrderId::generate();
        $this->assertFalse($orderId->equals($this->createMock(ValueObjectInterface::class)));
    }
}
