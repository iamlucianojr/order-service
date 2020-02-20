<?php

declare(strict_types=1);

namespace App\Order\Application\Command;

interface OrderDeliverCommandInterface
{
    public function getOrderId(): string;
}
