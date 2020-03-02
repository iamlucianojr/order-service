<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Persistence\ORM\Projections;

use App\Order\Domain\Event\OrderWasCanceled;
use App\Order\Domain\Event\OrderWasDelivered;
use App\Order\Domain\Event\OrderWasRequested;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;

final class OrderProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $readModel = $projector->readModel();
        $projector->fromStreams('event_stream')
            ->when([
                OrderWasRequested::class => function ($state, OrderWasRequested $event) use ($readModel) {
                    $readModel->stack('insert', [
                        'order_id' => $event->aggregateId(),
                        'establishment_id' => $event->establishment()->establishmentId()->toString(),
                        'catalog_flow_id' => $event->catalogFlow()->catalogFlowId()->toString(),
                        'catalog_flow_version' => $event->catalogFlow()->version(),
                        'table_identifier' => $event->tableIdentifier()->toString(),
                        'items' => $event->items()->__toString(),
                        'status' => $event->status()->toString(),
                    ]);
                },
                OrderWasCanceled::class => function ($state, OrderWasCanceled $event) use ($readModel) {
                    $readModel->stack(
                        'update',
                        [
                            'status' => $event->status()->toString(),
                        ],
                        [
                            'order_id' => $event->aggregateId(),
                        ]
                    );
                },
                OrderWasDelivered::class => function ($state, OrderWasDelivered $event) use ($readModel) {
                    $readModel->stack(
                        'update',
                        [
                            'status' => $event->status()->toString(),
                        ],
                        [
                            'order_id' => $event->aggregateId(),
                        ]
                    );
                },
            ]);

        return $projector;
    }
}
