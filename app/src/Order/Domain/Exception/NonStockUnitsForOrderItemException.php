<?php

declare(strict_types=1);

namespace App\Order\Domain\Exception;

use RuntimeException;
use Throwable;

final class NonStockUnitsForOrderItemException extends RuntimeException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
