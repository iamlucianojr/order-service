<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Persistence\ORM\Projections;

use stdClass;

interface OrderFinderInterface
{
    public function findAll(): array;

    public function findByUuid(string $orderId): ?stdClass;
}
