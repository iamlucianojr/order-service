<?php

declare(strict_types=1);

namespace App\Order\Application\Service;

use Psr\Log\LoggerInterface;

final class CheckIfThereAreStockUnits implements CheckIfThereAreStockUnitsInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(array $items): bool
    {
        $this->logger->debug('Check if there are stock units for the order');
        /*
         * Could receive a stock service client
         * Do the call to know about stock units for the order items
         */
        return true;
    }
}
