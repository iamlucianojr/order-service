<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Persistence\Repository;

use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\OrderId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\EventStore;
use RuntimeException;

final class OrderRepository extends AggregateRepository implements OrderRepositoryInterface
{
    public function __construct(EventStore $eventStore)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass(Order::class),
            new AggregateTranslator(),
        );
    }

    public function save(Order $order): void
    {
        $this->saveAggregateRoot($order);
    }

    public function get(OrderId $orderId): ?Order
    {
        $order = $this->getAggregateRoot($orderId->toString());
        if (null !== $order && !$order instanceof Order) {
            throw new RuntimeException(sprintf('Object returned by AggregateRepository %s is not an instance of Order', get_class($order)));
        }

        return $order;
    }

    public function findByCriteria(array $criteria): ?array
    {
        return [];
    }
}
