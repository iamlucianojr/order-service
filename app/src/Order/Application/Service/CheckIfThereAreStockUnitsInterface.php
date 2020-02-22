<?php

declare(strict_types=1);

namespace App\Order\Application\Service;

interface CheckIfThereAreStockUnitsInterface
{
    public function execute(array $items): bool;
}
