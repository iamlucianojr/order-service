<?php

declare(strict_types=1);

namespace App\Order\Application\Service;

final class StockUnit
{
    public function execute(array $items): bool
    {
        /*
         * Could receive a stock service client
         * Do the call to know about stock units for the order items
         */
        return true;
    }
}
