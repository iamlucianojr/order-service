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
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class OrderRequestHandler implements MessageHandlerInterface
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

    public function __invoke(OrderRequestCommandInterface $command): void
    {
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

        $this->repository->save($order);
    }
}
