<?php

declare(strict_types=1);

namespace App\Order\Domain\Model;

use App\Order\Domain\Event\OrderWasCanceled;
use App\Order\Domain\Event\OrderWasDelivered;
use App\Order\Domain\Event\OrderWasRequested;
use App\Order\Domain\Exception\CannotCancelDeliveredOrderException;
use App\Order\Domain\Exception\CannotDeliverCanceledOrderException;
use App\Order\Domain\ValueObject\CatalogFlow;
use App\Order\Domain\ValueObject\Establishment;
use App\Order\Domain\ValueObject\ItemCollection;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Domain\ValueObject\Status;
use App\Order\Domain\ValueObject\TableIdentifier;
use App\Shared\EntityInterface;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

final class Order extends AggregateRoot implements EntityInterface
{
    private OrderId $orderId;

    private Establishment $establishment;

    private CatalogFlow $catalogFlow;

    private TableIdentifier $tableIdentifier;

    private ItemCollection $items;

    private Status $status;

    public static function register(
        OrderId $orderId,
        Establishment $establishment,
        CatalogFlow $catalogFlow,
        TableIdentifier $tableIdentifier,
        ItemCollection $items
    ): Order {
        $self = new self();

        $self->recordThat(
            OrderWasRequested::withData(
                $orderId,
                $establishment,
                $catalogFlow,
                $tableIdentifier,
                $items,
                Status::waiting()
            )
        );

        return $self;
    }

    public function deliver(): Order
    {
        if ($this->status->status() === Status::canceled()->status()) {
            throw new CannotDeliverCanceledOrderException();
        }

        $this->recordThat(OrderWasDelivered::withData($this->orderId, Status::delivered()));

        $this->status = Status::delivered();

        return $this;
    }

    public function cancel(): Order
    {
        if ($this->status->status() === Status::delivered()->status()) {
            throw new CannotCancelDeliveredOrderException();
        }

        $this->recordThat(OrderWasCanceled::withData($this->orderId, Status::canceled()));

        $this->status = Status::canceled();

        return $this;
    }

    public function status(): Status
    {
        return $this->status;
    }

    public function items(): ItemCollection
    {
        return $this->items;
    }

    public function aggregateId(): string
    {
        return $this->orderId->toString();
    }

    public function establishment(): Establishment
    {
        return $this->establishment;
    }

    public function catalogFlow(): CatalogFlow
    {
        return $this->catalogFlow;
    }

    public function tableIdentifier(): TableIdentifier
    {
        return $this->tableIdentifier;
    }

    public function equals(EntityInterface $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->toArray() === $other->toArray();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->orderId->toString(),
            'establishment' => $this->establishment->toArray(),
            'catalog_flow' => $this->catalogFlow->toArray(),
            'table_identifier' => $this->tableIdentifier->toString(),
            'items' => $this->items->toArray(),
            'status' => $this->status->toString(),
        ];
    }

    protected function apply(AggregateChanged $event): void
    {
        switch (get_class($event)) {
            case OrderWasRequested::class:
                assert($event instanceof OrderWasRequested);
                $this->orderId = $event->orderId();
                $this->establishment = $event->establishment();
                $this->catalogFlow = $event->catalogFlow();
                $this->tableIdentifier = $event->tableIdentifier();
                $this->items = $event->items();
                $this->status = $event->status();
                break;

            case OrderWasCanceled::class:
                assert($event instanceof OrderWasCanceled);
                $this->status = $event->status();
                break;

            case OrderWasDelivered::class:
                assert($event instanceof OrderWasDelivered);
                $this->status = $event->status();
                break;
        }
    }
}
