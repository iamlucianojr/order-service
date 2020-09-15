<?php

declare(strict_types=1);

namespace App\Order\Domain\Event;

use App\Order\Domain\ValueObject\OrderId;
use App\Order\Domain\ValueObject\Status;
use Prooph\EventSourcing\AggregateChanged;

final class OrderWasCanceled extends AggregateChanged
{
    private OrderId $orderId;

    private Status $status;

    public function status(): Status
    {
        return Status::fromString($this->payload['status']);
    }

    public static function withData(
        OrderId $orderId,
        Status $status
    ): self {
        $event = self::occur($orderId->toString(), [
            'status' => $status->toString(),
        ]);

        $event->orderId = $orderId;
        $event->status = $status;

        return $event;
    }

    public function getOrderId(): string
    {
        return $this->orderId->toString();
    }

    public function getStatus(): string
    {
        return $this->status->toString();
    }
}
