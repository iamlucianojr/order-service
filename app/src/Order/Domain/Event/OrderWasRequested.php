<?php

declare(strict_types=1);

namespace App\Order\Domain\Event;

use App\Order\Domain\ValueObject\CatalogFlow;
use App\Order\Domain\ValueObject\Establishment;
use App\Order\Domain\ValueObject\ItemCollection;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Domain\ValueObject\Status;
use App\Order\Domain\ValueObject\TableIdentifier;
use Prooph\EventSourcing\AggregateChanged;

final class OrderWasRequested extends AggregateChanged
{
    private OrderId $orderId;

    private Establishment $establishment;

    private CatalogFlow $catalogFlow;

    private TableIdentifier $tableIdentifier;

    private ItemCollection $items;

    private Status $status;

    public function orderId(): OrderId
    {
        if (!isset($this->orderId)) {
            $this->orderId = OrderId::fromString($this->aggregateId());
        }

        return $this->orderId;
    }

    public function tableIdentifier(): TableIdentifier
    {
        return TableIdentifier::fromString($this->payload['table_identifier']);
    }

    public function establishment(): Establishment
    {
        return Establishment::fromArray($this->payload['establishment']);
    }

    public function catalogFlow(): CatalogFlow
    {
        return CatalogFlow::fromArray($this->payload['catalog_flow']);
    }

    public function items(): ItemCollection
    {
        return ItemCollection::fromArray($this->payload['items']);
    }

    public function status(): Status
    {
        return Status::fromString($this->payload['status']);
    }

    public static function withData(
        OrderId $orderId,
        Establishment $establishment,
        CatalogFlow $catalogFlow,
        TableIdentifier $tableIdentifier,
        ItemCollection $items,
        Status $status
    ): OrderWasRequested {
        $event = new self(
            $orderId->toString(),
            [
                'establishment' => $establishment->toArray(),
                'catalog_flow' => $catalogFlow->toArray(),
                'table_identifier' => $tableIdentifier->toString(),
                'items' => $items->toArray(),
                'status' => $status->toString(),
            ]
        );

        $event->orderId = $orderId;
        $event->establishment = $establishment;
        $event->catalogFlow = $catalogFlow;
        $event->tableIdentifier = $tableIdentifier;
        $event->items = $items;
        $event->status = $status;

        return $event;
    }

    public function getOrderId(): string
    {
        return $this->orderId->toString();
    }

    public function getEstablishment(): array
    {
        return $this->establishment->toArray();
    }

    public function getCatalogFlow(): array
    {
        return $this->catalogFlow->toArray();
    }

    public function getTableIdentifier(): string
    {
        return $this->tableIdentifier->toString();
    }

    public function getItems(): array
    {
        return $this->items->toArray();
    }

    public function getStatus(): string
    {
        return $this->status->toString();
    }
}
