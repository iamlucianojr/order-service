<?php

declare(strict_types=1);

namespace App\Order\Application\Command\Handler;

use App\Order\Application\Command\OrderRedoCommand;
use App\Order\Application\Service\CheckIfThereAreStockUnitsInterface;
use App\Order\Domain\Exception\NonStockUnitsForOrderItemException;
use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Infrastructure\Persistence\Repository\OrderRepositoryInterface;
use RuntimeException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class OrderRedoHandler implements MessageHandlerInterface
{
    private OrderRepositoryInterface $repository;

    private CheckIfThereAreStockUnitsInterface $stockChecker;

    public function __construct(
        OrderRepositoryInterface $repository,
        CheckIfThereAreStockUnitsInterface $stockChecker
    ) {
        $this->repository = $repository;
        $this->stockChecker = $stockChecker;
    }

    public function __invoke(OrderRedoCommand $command): void
    {
        $originalOrder = $this->repository->get(OrderId::fromString($command->getFromOrderId()));

        if (!$originalOrder instanceof Order) {
            throw new RuntimeException(sprintf('Could not find original order %s', $command->getFromOrderId()));
        }

        $stockUnits = $this->stockChecker->execute($originalOrder->items()->toArray());

        if (false === $stockUnits) {
            throw new NonStockUnitsForOrderItemException(sprintf('The order has items out of stock. Please chose another item', ));
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
