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
    private ProductName $name;
    private int $quantity;
    private int $version;

    private function __construct(
        UuidInterface $uuid,
        ProductName $name,
        ProductType $type,
        int $quantity,
        int $version
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->type = $type;
        $this->quantity = $quantity;
        $this->version = $version;
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

    public function version(): int
    {
        return $this->version;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public static function fromArray(array $data): self
    {
        Assertion::uuid($data['uuid']);

        return new self(
            Uuid::fromString($data['uuid']),
            ProductName::fromString($data['name']),
            ProductType::fromString($data['type']),
            (int) $data['quantity'],
            (int) $data['version']
        );
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'name' => $this->name->toString(),
            'type' => $this->type->toString(),
            'quantity' => $this->quantity,
            'version' => $this->version
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
