<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\CatalogFlow;
use PHPUnit\Framework\TestCase;

final class CatalogFlowTest extends TestCase
{
    public function testFromArray(): void
    {
        $catalogFlow = CatalogFlow::fromArray([
            'uuid' => '10d853df-15e2-460e-9892-da62361c116e',
            'version' => 3,
        ]);

        $this->assertNotEmpty($catalogFlow);
    }

    public function testToArray(): void
    {
    }

    public function testEquals(): void
    {
    }

    public function testToString(): void
    {
    }

    public function testVersion(): void
    {
    }

    public function testCatalogFlowId(): void
    {
    }
}
