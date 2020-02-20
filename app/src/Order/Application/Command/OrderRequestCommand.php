<?php

declare(strict_types=1);

namespace App\Order\Application\Command;

final class OrderRequestCommand implements OrderRequestCommandInterface
{
    private string $orderRequestId;
    private string $tableIdentifier;
    private array $establishment;
    private array $catalogFlow;
    private array $items;

    public function __construct(
        string $orderRequestId,
        array $establishment,
        array $catalogFlow,
        string $tableIdentifier,
        array $items
    ) {
        $this->orderRequestId = $orderRequestId;
        $this->tableIdentifier = $tableIdentifier;
        $this->establishment = $establishment;
        $this->catalogFlow = $catalogFlow;
        $this->items = $items;
    }

    public function getOrderRequestId(): string
    {
        return $this->orderRequestId;
    }

    public function getTableIdentifier(): string
    {
        return $this->tableIdentifier;
    }

    public function getEstablishment(): array
    {
        return $this->establishment;
    }

    public function getCatalogFlow(): array
    {
        return $this->catalogFlow;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
