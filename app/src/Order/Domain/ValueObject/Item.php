<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

use App\Shared\ValueObjectInterface;
use Assert\Assertion;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Item implements ValueObjectInterface
{
    private UuidInterface $uuid;
    private ProductType $type;
    private int $quantity;

    private function __construct(
        UuidInterface $uuid,
        ProductType $type,
        int $quantity
    ) {
        $this->uuid = $uuid;
        $this->type = $type;
        $this->quantity = $quantity;
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function type(): ProductType
    {
        return $this->type;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public static function fromArray(array $data): self
    {
        Assertion::uuid($data['uuid']);

        return new self(
            Uuid::fromString($data['uuid']),
            ProductType::fromString($data['type']),
            (int) $data['quantity']
        );
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'type' => $this->type->toString(),
            'quantity' => $this->quantity,
        ];
    }

    public function equals(ValueObjectInterface $valueObject): bool
    {
        if (!$valueObject instanceof self) {
            return false;
        }

        return $valueObject->toArray() === $valueObject->toArray();
    }
}
