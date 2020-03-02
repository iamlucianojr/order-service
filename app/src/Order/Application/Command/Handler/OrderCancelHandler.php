<?php

declare(strict_types=1);

namespace App\Order\Application\Command\Handler;

use App\Order\Application\Command\OrderCancelCommandInterface;
use App\Order\Domain\Exception\CannotCancelDeliveredOrderException;
use App\Order\Domain\Model\Order;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Infrastructure\Persistence\Repository\OrderRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class OrderCancelHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    private OrderRepositoryInterface $repository;

    public function __construct(OrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(OrderCancelCommandInterface $command): void
    {
        $order = $this->repository->get(OrderId::fromString($command->getOrderId()));
        assert($order instanceof Order);
        try {
            $order->cancel();
        } catch (CannotCancelDeliveredOrderException $exception) {
            $this->logger->warning(sprintf('Order %s could not be canceled because it was deliver', $command->getOrderId()));
        }

        $this->repository->save($order);
    }
}
