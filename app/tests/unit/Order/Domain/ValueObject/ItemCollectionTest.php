<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\Item;
use App\Order\Domain\ValueObject\ItemCollection;
use App\Order\Domain\ValueObject\ProductType;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class ItemCollectionTest extends TestCase
{
    public function testLast(): void
    {
        $item = $this->item();
        $collection = ItemCollection::fromItems($item);
        $this->assertEquals($item, $collection->last());
    }

    public function testContains(): void
    {
        $item = $this->item();
        $collection = ItemCollection::fromItems($item);
        $this->assertContains($item, $collection->items());
        $this->assertContainsOnly(Item::class, $collection->items());
    }

    public function testFromArray(): void
    {
        $collection = ItemCollection::fromArray([
            [
                'uuid' => Uuid::uuid4()->toString(),
                'name' => 'Gatorade',
                'type' => ProductType::DRINK,
                'quantity' => 1,
                'version' => 2,
            ],
            [
                'uuid' => Uuid::uuid4()->toString(),
                'name' => 'Nacho supreme',
                'type' => ProductType::FOOD,
                'quantity' => 2,
                'version' => 5,
            ],
        ]);
        $this->assertCount(2, $collection);
    }

    public function testItems(): void
    {
        $item = $this->item();
        $collection = ItemCollection::fromItems($item);
        $this->assertNotEmpty($collection->items());
    }

    public function testFromItems(): void
    {
        $item = $this->item();
        $collection = ItemCollection::fromItems($item);
        $this->assertNotEmpty($collection->items());
    }

    public function testCount(): void
    {
        $collection = ItemCollection::emptyList();
        $this->assertEquals(0, $collection->count());
        $newCollection = $collection->push($this->item());
        $this->assertEquals(1, $newCollection->count());
    }

    public function testEmptyList(): void
    {
        $collection = ItemCollection::emptyList();
        $this->assertEmpty($collection->count());
    }

    public function testPop(): void
    {
        $item = $this->item();
        $collection = ItemCollection::fromItems($item);
        $collection = $collection->pop();
        $this->assertEmpty($collection->items());
    }

    public function testFirst(): void
    {
        $item = $this->item();
        $collection = ItemCollection::fromItems($item);
        $this->assertEquals($item, $collection->first());
    }

    public function testPush(): void
    {
        $item = $this->item();
        $collection = ItemCollection::emptyList();
        $collection = $collection->push($item);
        $this->assertContains($item, $collection->items());
        $this->assertEquals($item, $collection->first());
        $this->assertEquals($item, $collection->last());
    }

    public function testToArray(): void
    {
        $item = $this->item();
        $collection = ItemCollection::fromItems($item);
        $this->assertIsArray($collection->toArray());
    }

    public function item(): Item
    {
        return Item::fromArray([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'Vegan Chili beans',
            'type' => ProductType::FOOD,
            'quantity' => 2,
            'version' => 1,
        ]);
    }
}
