<?php

declare(strict_types=1);

namespace App\Order\Application\Command;

final class OrderRedoCommand implements OrderRedoCommandInterface
{
    private string $orderId;
    private string $fromOrderId;

    public function __construct(
        string $orderId,
        string $fromOrderId
    ) {
        $this->orderId = $orderId;
        $this->fromOrderId = $fromOrderId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getFromOrderId(): string
    {
        return $this->fromOrderId;
    }
}
