<?php

declare(strict_types=1);

namespace App\Tests\Order\Application\Command\Handler;

use App\Order\Application\Command\Handler\OrderRequestHandler;
use App\Order\Application\Command\OrderRequestCommand;
use App\Order\Application\Service\CheckIfThereAreStockUnitsInterface;
use App\Order\Domain\Exception\NonStockUnitsForOrderItemException;
use App\Order\Domain\ValueObject\ProductType;
use App\Order\Infrastructure\Persistence\Repository\OrderRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class OrderRequestHandlerTest extends TestCase
{
    private OrderRequestCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function testHandlerGivenCommandWithInvalidValuesShouldThrowNonStockUnitsForOrderItemException(): void
    {
        $repository = $this->getMockBuilder(OrderRepositoryInterface::class)->getMock();
        $stockChecker = $this->getMockBuilder(CheckIfThereAreStockUnitsInterface::class)->setMethods(['execute'])->getMock();
        $stockChecker->method('execute')->willReturn(false);

        $this->expectException(NonStockUnitsForOrderItemException::class);
        $handler = new OrderRequestHandler(
            $repository,
            $stockChecker
        );

        $handler->__invoke($this->command);
    }
}
