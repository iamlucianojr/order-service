<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

final class ProductType
{
    public const DRINK = 'drink';
    public const FOOD = 'food';

    private string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function fromString(string $type): self
    {
        return new self($type);
    }

    public function type(): string
    {
        return $this->type;
    }

    public static function drink(): self
    {
        return new static(self::DRINK);
    }

    public static function food(): self
    {
        return new static(self::FOOD);
    }

    public function toString(): string
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
