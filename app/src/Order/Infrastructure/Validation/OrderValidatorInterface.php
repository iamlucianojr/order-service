<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Validation;

interface OrderValidatorInterface
{
    public function validate(): bool;
}
