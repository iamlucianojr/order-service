<?php

declare(strict_types=1);

namespace App\Order\Application\Command;

interface OrderRedoCommandInterface
{
    public function getOrderId(): string;

    public function getFromOrderId(): string;
}
