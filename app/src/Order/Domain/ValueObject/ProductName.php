<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

final class ProductName
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }

    public function type(): string
    {
        return $this->name;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
