<?php

declare(strict_types=1);

namespace App\Tests\unit\Order\Domain\Model;

use App\Order\Domain\ValueObject\CatalogFlow;
use App\Order\Domain\ValueObject\Establishment;
use App\Order\Domain\ValueObject\Item;
use App\Order\Domain\ValueObject\ItemCollection;
use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Domain\ValueObject\ProductType;
use App\Order\Domain\ValueObject\Status;
use App\Order\Domain\ValueObject\TableIdentifier;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class OrderTest extends TestCase
{
    public function testOrderRegisterCreatesWaitingOrder(): void
    {
        $establishment = Establishment::fromArray([
            'uuid' => Uuid::uuid4()->toString()
        ]);

        $catalogFlow = CatalogFlow::fromArray([
            'uuid' => Uuid::uuid4()->toString(),
            'version' => 1
        ]);

        $tableIdentifier = TableIdentifier::fromString('TABLE01');

        $itemCollection = ItemCollection::fromArray([
            [
                'uuid' => Uuid::uuid4(),
                'type' => ProductType::FOOD,
                'quantity' => rand(1, 5)
            ],
            [
                'uuid' => Uuid::uuid4(),
                'type' => ProductType::DRINK,
                'quantity' => rand(5, 10)
            ]
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

    public function testOrderCancel(): void
    {
        $order = $this->getAnOrder();
        $order->cancel();
        $this->assertEquals($order->status(), Status::canceled());
    }

    private function getAnOrder(): Order
    {
        return Order::register(
            OrderId::fromString(Uuid::uuid4()->toString()),
            Establishment::fromArray(['uuid' => Uuid::uuid4()->toString()]),
            CatalogFlow::fromArray([
                'uuid' => Uuid::uuid4()->toString(),
                'version' => 1
            ]),
            TableIdentifier::fromString('TABLE_02'),
            ItemCollection::fromItems(
                Item::fromArray([
                    'uuid' => Uuid::uuid4(),
                    'type' => ProductType::FOOD,
                    'quantity' => rand(1, 5)
                ]),
                Item::fromArray([
                    'uuid' => Uuid::uuid4(),
                    'type' => ProductType::DRINK,
                    'quantity' => rand(5, 10)
                ])
            )
        );
    }
}
