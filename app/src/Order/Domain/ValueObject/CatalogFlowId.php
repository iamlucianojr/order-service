<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

use App\Shared\ValueObjectInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class CatalogFlowId implements ValueObjectInterface
{
    private UuidInterface $uuid;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $catalogId): self
    {
        return new self(Uuid::fromString($catalogId));
    }

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return get_class($this) === get_class($other) && $this->uuid->equals($other->uuid);
    }
}
