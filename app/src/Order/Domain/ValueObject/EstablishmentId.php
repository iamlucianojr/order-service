<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

use App\Shared\ValueObjectInterface;
use function get_class;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class EstablishmentId implements ValueObjectInterface
{
    private UuidInterface $uuid;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $establishmentId): self
    {
        return new self(Uuid::fromString($establishmentId));
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
        if (!$other instanceof self) {
            return false;
        }

        return get_class($this) === get_class($other) && $this->uuid->equals($other->uuid);
    }
}
