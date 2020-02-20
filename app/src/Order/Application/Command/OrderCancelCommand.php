<?php

declare(strict_types=1);

namespace App\Order\Application\Command;

final class OrderCancelCommand implements OrderCancelCommandInterface
{
    private string $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }
}
