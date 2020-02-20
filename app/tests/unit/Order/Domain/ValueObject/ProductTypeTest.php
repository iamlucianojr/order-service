<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\ProductType;
use PHPUnit\Framework\TestCase;

final class ProductTypeTest extends TestCase
{

    public function testFromString(): void
    {
        $productType = ProductType::fromString('food');
        $this->assertEquals(ProductType::FOOD, $productType->toString());
    }

    public function testDrink(): void
    {
        $productType = ProductType::drink();
        $this->assertEquals(ProductType::DRINK, $productType->toString());
    }

    public function testFood(): void
    {
        $productType = ProductType::food();
        $this->assertEquals(ProductType::FOOD, $productType->toString());
    }
}
