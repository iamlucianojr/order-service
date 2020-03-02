<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

use App\Shared\ValueObjectInterface;
use Assert\Assertion;

final class CatalogFlow implements ValueObjectInterface
{
    private CatalogFlowId $catalogFlowId;

    private int $version;

    private function __construct(
        CatalogFlowId $catalogFlowId,
        int $version
    ) {
        $this->catalogFlowId = $catalogFlowId;
        $this->version = $version;
    }

    public static function fromArray(array $data): self
    {
        Assertion::uuid($data['uuid']);
        Assertion::integer($data['version']);

        return new self(
            CatalogFlowId::fromString($data['uuid']),
            $data['version']
        );
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->catalogFlowId->toString(),
            'version' => $this->version,
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

    public function catalogFlowId(): CatalogFlowId
    {
        return $this->catalogFlowId;
    }

    public function version(): int
    {
        return $this->version;
    }
}
