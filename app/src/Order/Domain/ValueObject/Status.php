<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

final class Status
{
    public const WAITING = 'waiting';
    public const DELIVERED = 'delivered';
    public const CANCELED = 'canceled';

    private string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function fromString(string $status): self
    {
        return new self($status);
    }

    public function status(): string
    {
        return $this->status;
    }

    public static function waiting(): self
    {
        return new static(self::WAITING);
    }

    public static function delivered(): self
    {
        return new static(self::DELIVERED);
    }

    public static function canceled(): self
    {
        return new static(self::CANCELED);
    }

    public function toString(): string
    {
        return $this->status;
    }

    public function __toString(): string
    {
        return $this->status;
    }
}
