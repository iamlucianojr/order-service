<?php

namespace App\Order\Application\Service;

use App\Order\Domain\ValueObject\Establishment;

interface EstablishmentServiceInterface
{
    public function validateEstablishment(Establishment $establishment): bool;
}
