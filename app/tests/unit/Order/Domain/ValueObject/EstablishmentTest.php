<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\Establishment;
use App\Order\Domain\ValueObject\EstablishmentId;
use PHPUnit\Framework\TestCase;

final class EstablishmentTest extends TestCase
{
    public function testFromArray(): void
    {
        $establishment = Establishment::fromArray([
            'uuid' => EstablishmentId::generate()->toString()
        ]);

        $this->assertNotEmpty($establishment->establishmentId()->toString());
    }

    public function testToArray(): void
    {
        $uuid =  EstablishmentId::generate();
        $establishment = Establishment::fromArray([
            'uuid' => $uuid->toString()
        ]);

        $this->assertEquals([
            'uuid' => $uuid->toString()
        ], $establishment->toArray());
    }
}
