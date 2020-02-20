<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Persistence\Repository;

use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\OrderId;

interface OrderRepositoryInterface
{
    public function save(Order $order): void;

    public function get(OrderId $orderId): ?Order;

    public function findByCriteria(array $criteria): ?array;
}
