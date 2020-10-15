<?php

declare(strict_types=1);

namespace App\Order\Application\Service;

use App\Order\Domain\ValueObject\CatalogFlow;
use App\Order\Domain\ValueObject\Establishment;

final class EstablishmentService implements EstablishmentServiceInterface
{
    public function validateEstablishment(Establishment $establishment): bool
    {
        //todo the logic to check if establishment exists
    }

    public function validateCatalog(Establishment $establishment, CatalogFlow $catalogFlow): bool
    {
        /**
         * todo add logic to validate the use of catalog
         * Catalog has to be enabled and be valid in the moment (different settings)
        */
    }
}
