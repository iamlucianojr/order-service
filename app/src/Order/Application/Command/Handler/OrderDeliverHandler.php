<?php

declare(strict_types=1);

namespace App\Order\Application\Command\Handler;

use App\Order\Application\Command\OrderDeliverCommandInterface;
use App\Order\Domain\Exception\CannotDeliverCanceledOrderException;
use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Infrastructure\Persistence\Repository\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OrderDeliverHandler implements MessageHandlerInterface
{
    private OrderRepositoryInterface $repository;

    private MessageBusInterface $eventBus;

    private LoggerInterface $logger;

    public function __construct(
        OrderRepositoryInterface $repository,
        MessageBusInterface $eventBus,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
        $this->logger = $logger;
    }

    public function __invoke(OrderDeliverCommandInterface $command): void
    {
        $order = $this->repository->get(OrderId::fromString($command->getOrderId()));
        assert($order instanceof Order);
        try {
            $order->deliver();

            $this->eventBus->dispatch(...$order->getDomainEvents());
        } catch (CannotDeliverCanceledOrderException $exception) {
            $this->logger->warning(
                sprintf('Order %s could not be delivered because it was cancel', $command->getOrderId()),
            );
            throw $exception;
        }

        $this->repository->save($order);
    }
}
