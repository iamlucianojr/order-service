<?php

declare(strict_types=1);

namespace App\Tests\Order\Application\Command;

use App\Order\Application\Command\OrderRequestCommand;
use App\Order\Domain\ValueObject\ProductType;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class OrderRequestCommandTest extends TestCase
{
    private OrderRequestCommand $command;

    protected function setUp(): void
    {
        $this->command = new OrderRequestCommand(
            Uuid::uuid4()->toString(),
            [
                'uuid' => Uuid::uuid4()->toString(),
            ],
            [
                'uuid' => Uuid::uuid4()->toString(),
                'version' => 3,
            ],
            'TAB_A01',
            [
                [
                    'uuid' => Uuid::uuid4()->toString(),
                    'type' => ProductType::DRINK,
                    'quantity' => 1,
                ],
                [
                    'uuid' => Uuid::uuid4()->toString(),
                    'type' => ProductType::FOOD,
                    'quantity' => 2,
                ],
            ]
        );
    }

    public function testGetOrderRequestId(): void
    {
        $this->assertIsString($this->command->getOrderRequestId());
    }

    public function testGetEstablishment(): void
    {
        $this->assertIsArray($this->command->getEstablishment());
        $this->assertCount(1, $this->command->getEstablishment());
    }

    public function testGetTableIdentifier(): void
    {
        $this->assertIsString($this->command->getTableIdentifier());
    }

    public function testGetCatalogFlow(): void
    {
        $this->assertIsArray($this->command->getCatalogFlow());
        $this->assertCount(2, $this->command->getCatalogFlow());
    }

    public function testGetItems(): void
    {
        $this->assertIsArray($this->command->getItems());
        $this->assertCount(2, $this->command->getItems());
    }
}
