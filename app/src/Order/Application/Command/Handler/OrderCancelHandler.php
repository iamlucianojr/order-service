<?php

declare(strict_types=1);

namespace App\Order\Application\Command\Handler;

use App\Order\Application\Command\OrderCancelCommand;
use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Infrastructure\Persistence\Repository\OrderRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class OrderCancelHandler implements MessageHandlerInterface
{
    private OrderRepositoryInterface $repository;

    public function __construct(OrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(OrderCancelCommand $command): void
    {
        $order = $this->repository->get(OrderId::fromString($command->getOrderId()));
        assert($order instanceof Order);
        $order->cancel();

        $this->repository->save($order);
    }
}
