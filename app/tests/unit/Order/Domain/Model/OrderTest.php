<?php

declare(strict_types=1);

namespace App\Tests\unit\Order\Domain\Model;

use App\Order\Domain\Exception\CannotCancelDeliveredOrderException;
use App\Order\Domain\Exception\CannotDeliverCanceledOrderException;
use App\Order\Domain\ValueObject\CatalogFlow;
use App\Order\Domain\ValueObject\Establishment;
use App\Order\Domain\ValueObject\Item;
use App\Order\Domain\ValueObject\ItemCollection;
use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Domain\ValueObject\ProductType;
use App\Order\Domain\ValueObject\Status;
use App\Order\Domain\ValueObject\TableIdentifier;
use App\Shared\EntityInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class OrderTest extends TestCase
{
    public function testOrderRegisterCreatesWaitingOrder(): void
    {
        $establishment = Establishment::fromArray([
            'uuid' => Uuid::uuid4()->toString(),
        ]);

        $catalogFlow = CatalogFlow::fromArray([
            'uuid' => Uuid::uuid4()->toString(),
            'version' => 1,
        ]);

        $tableIdentifier = TableIdentifier::fromString('TABLE01');

        $itemCollection = ItemCollection::fromArray([
            [
                'uuid' => Uuid::uuid4(),
                'name' => 'French fries',
                'type' => ProductType::FOOD,
                'quantity' => rand(1, 5),
                'version' => rand(1, 4)
            ],
            [
                'uuid' => Uuid::uuid4(),
                'name' => 'Mojito',
                'type' => ProductType::DRINK,
                'quantity' => rand(5, 10),
                'version' => rand(1, 4)
            ],
        ]);

        $order = Order::register(
            OrderId::fromString(Uuid::uuid4()->toString()),
            $establishment,
            $catalogFlow,
            $tableIdentifier,
            $itemCollection
        );

        $this->assertEquals(Status::waiting(), $order->status());
        $this->assertEquals($establishment, $order->establishment());
        $this->assertEquals($catalogFlow, $order->catalogFlow());
        $this->assertEquals($tableIdentifier, $order->tableIdentifier());
    }

    public function testOrderDeliver(): void
    {
        $order = $this->getAnOrder();
        $order->deliver();
        $this->assertEquals($order->status(), Status::delivered());
    }

    public function testOrderDeliverUsingAlreadyCanceledOrder(): void
    {
        $this->expectException(CannotDeliverCanceledOrderException::class);
        $order = $this->getAnOrder();
        $order->cancel();
        $order->deliver();
    }

    public function testOrderCancel(): void
    {
        $order = $this->getAnOrder();
        $order->cancel();
        $this->assertEquals($order->status(), Status::canceled());
    }

    public function testOrderCancelUsingAlreadyDeliveredOrder(): void
    {
        $this->expectException(CannotCancelDeliveredOrderException::class);
        $order = $this->getAnOrder();
        $order->deliver();
        $order->cancel();
    }

    public function testItems(): void
    {
        $order = $this->getAnOrder();
        $this->assertInstanceOf(ItemCollection::class, $order->items());
    }

    public function testEquals(): void
    {
        $orderOne = $this->getAnOrder();
        $orderTwo = $this->getAnOrder();

        $this->assertFalse($orderOne->equals($orderTwo));
        $this->assertFalse($orderTwo->equals($orderOne));

        $clonedOrder = clone $orderOne;
        $this->assertEquals($orderOne, $clonedOrder);
    }

    public function testEqualsUsingDifferentClass(): void
    {
        $orderOne = $this->getAnOrder();

        $this->assertFalse($orderOne->equals($this->createMock(EntityInterface::class)));
    }

    public function testAggregateId(): void
    {
        $orderUuid = Uuid::uuid4()->toString();
        $order = Order::register(
            OrderId::fromString($orderUuid),
            Establishment::fromArray(['uuid' => Uuid::uuid4()->toString()]),
            CatalogFlow::fromArray([
                'uuid' => Uuid::uuid4()->toString(),
                'version' => 1,
            ]),
            TableIdentifier::fromString('TABLE_3'),
            ItemCollection::fromItems(
                Item::fromArray([
                    'uuid' => Uuid::uuid4(),
                    'name' => 'Chicken wings',
                    'type' => ProductType::FOOD,
                    'quantity' => rand(1, 5),
                    'version' => rand(2, 3),
                ]),
                Item::fromArray([
                    'uuid' => Uuid::uuid4(),
                    'name' => 'Lemon Caipirinha',
                    'type' => ProductType::DRINK,
                    'quantity' => rand(5, 10),
                    'version' => rand(1, 3),
                ])
            )
        );

        $this->assertSame($orderUuid, $order->aggregateId());
    }

    private function getAnOrder(): Order
    {
        return Order::register(
            OrderId::fromString(Uuid::uuid4()->toString()),
            Establishment::fromArray(['uuid' => Uuid::uuid4()->toString()]),
            CatalogFlow::fromArray([
                'uuid' => Uuid::uuid4()->toString(),
                'version' => 1,
            ]),
            TableIdentifier::fromString('TABLE_02'),
            ItemCollection::fromItems(
                Item::fromArray([
                    'uuid' => Uuid::uuid4(),
                    'name' => 'Tasty burger',
                    'type' => ProductType::FOOD,
                    'quantity' => rand(1, 5),
                    'version' => rand(1, 3),
                ]),
                Item::fromArray([
                    'uuid' => Uuid::uuid4(),
                    'name' => 'Classic Margarita',
                    'type' => ProductType::DRINK,
                    'quantity' => rand(5, 10),
                    'version' => rand(1, 3),
                ])
            )
        );
    }
}
