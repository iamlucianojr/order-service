<?php

declare(strict_types=1);

namespace App\Order\Application\Command\Handler;

use App\Order\Application\Command\OrderRedoCommand;
use App\Order\Application\Service\StockUnit;
use App\Order\Domain\Exception\NonStockUnitsForOrderItemException;
use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Infrastructure\Persistence\Repository\OrderRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class OrderRedoHandler implements MessageHandlerInterface
{
    private OrderRepositoryInterface $repository;
    /**
     * @var StockUnit
     */
    private StockUnit $stockUnit;

    public function __construct(
        OrderRepositoryInterface $repository,
        StockUnit $stockUnit
    ) {
        $this->repository = $repository;
        $this->stockUnit = $stockUnit;
    }

    public function __invoke(OrderRedoCommand $command): void
    {
        $originalOrder = $this->repository->get(OrderId::fromString($command->getFromOrderId()));

        $stockUnits = $this->stockUnit->execute($originalOrder->items()->toArray());

        if (false === $stockUnits) {
            throw new NonStockUnitsForOrderItemException(
                sprintf(
                    'The order has items out of stock. Please chose another item',
                )
            );
        }

        $order = Order::register(
            OrderId::fromString($command->getOrderId()),
            $originalOrder->establishment(),
            $originalOrder->catalogFlow(),
            $originalOrder->tableIdentifier(),
            $originalOrder->items()
        );

        $this->repository->save($order);
    }
}
