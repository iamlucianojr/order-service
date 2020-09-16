<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

use App\Shared\ValueObjectInterface;
use Countable;

final class ItemCollection implements ValueObjectInterface, Countable
{
    /** @var array Item */
    private array $items;

    public function __construct(Item ...$items)
    {
        $this->items = $items;
    }

    public static function fromArray(array $items): self
    {
        return new self(...array_map(static function (array $items) {
            return Item::fromArray($items);
        }, $items));
    }

    public static function fromItems(Item ...$items): self
    {
        return new self(...$items);
    }

    public static function emptyList(): self
    {
        return new self();
    }

    public function push(Item $item): self
    {
        $copy = clone $this;
        $copy->items[] = $item;

        return $copy;
    }

    public function pop(): self
    {
        $copy = clone $this;
        array_pop($copy->items);

        return $copy;
    }

    public function first(): ?Item
    {
        return $this->items[0] ?? null;
    }

    public function last(): ?Item
    {
        if (0 === count($this->items)) {
            return null;
        }

        return $this->items[count($this->items) - 1];
    }

    public function contains(Item $item): bool
    {
        foreach ($this->items as $existingItem) {
            if ($existingItem->equals($item)) {
                return true;
            }
        }

        return false;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return array_map(static function (Item $item) {
            return $item->toArray();
        }, $this->items);
    }

    public function equals(ValueObjectInterface $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->toArray() === $other->toArray();
    }

    public function __toString(): string
    {
        return (string) json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function sameValueAs(ValueObjectInterface $other): bool
    {
        return $this->equals($other);
    }
}
