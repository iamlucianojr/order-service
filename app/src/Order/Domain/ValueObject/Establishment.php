<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

use App\Shared\ValueObjectInterface;
use Assert\Assertion;

final class Establishment implements ValueObjectInterface
{
    private EstablishmentId $establishmentId;

    private function __construct(EstablishmentId $establishmentId)
    {
        $this->establishmentId = $establishmentId;
    }

    public function establishmentId(): EstablishmentId
    {
        return $this->establishmentId;
    }

    public static function fromArray(array $data): self
    {
        Assertion::uuid($data['uuid']);

        return new self(EstablishmentId::fromString($data['uuid']));
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->establishmentId->toString()
        ];
    }

    public function equals(ValueObjectInterface $valueObject): bool
    {
        if (!$valueObject instanceof self) {
            return false;
        }

        return $this->toArray() === $valueObject->toArray();
    }

    public function __toString(): string
    {
        return (string) json_encode($this->toArray());
    }
}
