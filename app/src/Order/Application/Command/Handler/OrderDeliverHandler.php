<?php

declare(strict_types=1);

namespace App\Order\Application\Command\Handler;

use App\Order\Application\Command\OrderDeliverCommandInterface;
use App\Order\Domain\Exception\CannotDeliverCanceledOrderException;
use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Infrastructure\Persistence\Repository\OrderRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class OrderDeliverHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private OrderRepositoryInterface $repository;

    public function __construct(OrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(OrderDeliverCommandInterface $command): void
    {
        $order = $this->repository->get(OrderId::fromString($command->getOrderId()));
        assert($order instanceof Order);
        try {
            $order->deliver();
        } catch (CannotDeliverCanceledOrderException $exception) {
            $this->logger->warning(
                sprintf('Order %s could not be delivered because it was cancel', $command->getOrderId()),
            );
            throw $exception;
        }

        $this->repository->save($order);
    }
}
