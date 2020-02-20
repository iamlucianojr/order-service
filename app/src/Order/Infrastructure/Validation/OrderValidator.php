<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Validation;

use App\Order\Infrastructure\Persistence\Repository\OrderRepositoryInterface;
use Symfony\Component\Intl\Exception\MethodNotImplementedException;

final class OrderValidator implements OrderValidatorInterface
{
    private OrderRepositoryInterface $repository;

    public function __construct(OrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function validate(): bool
    {
        // TODO: Implement validate() method.
        // i.e.: Call the service layer to check if we have stock for the items in the order
        throw new MethodNotImplementedException(__METHOD__);
    }
}
