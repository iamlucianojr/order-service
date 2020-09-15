<?php

declare(strict_types=1);

namespace App\Order\Application\Command\Handler;

use App\Order\Application\Command\OrderRedoCommandInterface;
use App\Order\Application\Service\CheckIfThereAreStockUnitsInterface;
use App\Order\Domain\Exception\NonStockUnitsForOrderItemException;
use App\Order\Domain\Exception\OrderNotFoundException;
use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Infrastructure\Persistence\Repository\OrderRepositoryInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OrderRedoHandler implements MessageHandlerInterface
{
    private OrderRepositoryInterface $repository;

    private CheckIfThereAreStockUnitsInterface $stockChecker;

    private MessageBusInterface $eventBus;

    private LoggerInterface $logger;

    public function __construct(
        OrderRepositoryInterface $repository,
        CheckIfThereAreStockUnitsInterface $stockChecker,
        MessageBusInterface $eventBus,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->stockChecker = $stockChecker;
        $this->eventBus = $eventBus;
        $this->logger = $logger;
    }

    public function __invoke(OrderRedoCommandInterface $command): void
    {
        $originalOrder = $this->repository->get(OrderId::fromString($command->getFromOrderId()));

        if (!$originalOrder instanceof Order) {
            throw new OrderNotFoundException(sprintf('Could not find original order %s', $command->getFromOrderId()));
        }

        $stockUnits = $this->stockChecker->execute($originalOrder->items()->toArray());

        if (false === $stockUnits) {
            throw new NonStockUnitsForOrderItemException('The order has items out of stock. Please chose another item');
        }

        try {
            $order = Order::register(
                OrderId::fromString($command->getOrderId()),
                $originalOrder->establishment(),
                $originalOrder->catalogFlow(),
                $originalOrder->tableIdentifier(),
                $originalOrder->items()
            );
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Could not redo order %s', $originalOrder->aggregateId())
            );
        }

        $this->eventBus->dispatch(...$order->getDomainEvents());

        $this->repository->save($order);
    }
}
