<?php

declare(strict_types=1);

namespace App\Shared;

interface EntityInterface
{
    public function equals(EntityInterface $other): bool;
}
