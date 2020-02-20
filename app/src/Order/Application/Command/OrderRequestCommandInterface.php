<?php

declare(strict_types=1);

namespace App\Order\Application\Command;

interface OrderRequestCommandInterface
{
    public function getOrderRequestId(): string;

    public function getTableIdentifier(): string;

    public function getEstablishment(): array;

    public function getCatalogFlow(): array;

    public function getItems(): array;
}
