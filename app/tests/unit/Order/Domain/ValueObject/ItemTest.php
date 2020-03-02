<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\Item;
use App\Shared\ValueObjectInterface;
use PHPUnit\Framework\TestCase;

final class ItemTest extends TestCase
{
    public function testFromArray(): void
    {
        $item = Item::fromArray([
            'uuid' => 'ba69029b-7623-4b3b-997b-a106444105a1',
            'type' => 'drink',
            'quantity' => 2,
        ]);

        $this->assertEquals('ba69029b-7623-4b3b-997b-a106444105a1', $item->uuid()->toString());
        $this->assertEquals('drink', $item->type()->toString());
        $this->assertEquals(2, $item->quantity());
    }

    public function testToArray(): void
    {
        $data = [
            'uuid' => '6bde3b2b-66f6-4576-9117-22069293ee94',
            'type' => 'food',
            'quantity' => 1,
        ];

        $item = Item::fromArray($data);

        $this->assertEquals($data, $item->toArray());
    }

    public function testEquals(): void
    {
        $data = [
            'uuid' => '6bde3b2b-66f6-4576-9117-22069293ee94',
            'type' => 'food',
            'quantity' => 1,
        ];

        $itemOne = Item::fromArray($data);
        $itemTwo = Item::fromArray($data);
        $this->assertTrue($itemOne->equals($itemTwo));
    }

    public function testEqualsWithDifferentClass(): void
    {
        $data = [
            'uuid' => '93a443bf-b936-493d-87c0-e3ad93c874a4',
            'type' => 'drink',
            'quantity' => 4,
        ];

        $itemOne = Item::fromArray($data);
        $this->assertFalse($itemOne->equals($this->createMock(ValueObjectInterface::class)));
    }
}
