<?php

declare(strict_types=1);

namespace App\Order\Application\Command;

interface OrderCancelCommandInterface
{
    public function getOrderId(): string;
}
