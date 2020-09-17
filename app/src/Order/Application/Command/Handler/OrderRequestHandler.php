<?php

declare(strict_types=1);

namespace App\Order\Application\Command\Handler;

use App\Order\Application\Command\OrderRequestCommandInterface;
use App\Order\Application\Service\CheckIfThereAreStockUnitsInterface;
use App\Order\Domain\Exception\NonStockUnitsForOrderItemException;
use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\CatalogFlow;
use App\Order\Domain\ValueObject\Establishment;
use App\Order\Domain\ValueObject\ItemCollection;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Domain\ValueObject\TableIdentifier;
use App\Order\Infrastructure\Persistence\Repository\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OrderRequestHandler implements MessageHandlerInterface
{
    private OrderRepositoryInterface $repository;

    private CheckIfThereAreStockUnitsInterface $stockChecker;

    private LoggerInterface $logger;

    private MessageBusInterface $eventBus;

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

    public function __invoke(OrderRequestCommandInterface $command): void
    {
        $this->logger->debug('Received command to order');
        $stockUnits = $this->stockChecker->execute($command->getItems());

        if (false === $stockUnits) {
            throw new NonStockUnitsForOrderItemException(sprintf('The order has items out of stock. Please chose another item'));
        }

        $order = Order::register(
            OrderId::fromString($command->getOrderRequestId()),
            Establishment::fromArray($command->getEstablishment()),
            CatalogFlow::fromArray($command->getCatalogFlow()),
            TableIdentifier::fromString($command->getTableIdentifier()),
            ItemCollection::fromArray($command->getItems())
        );

        $this->eventBus->dispatch(...$order->getDomainEvents());

        $this->logger->debug("Order {$command->getOrderRequestId()} requested");

        $this->repository->save($order);
    }
}
