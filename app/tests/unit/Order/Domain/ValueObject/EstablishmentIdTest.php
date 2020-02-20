<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\EstablishmentId;
use PHPUnit\Framework\TestCase;

final class EstablishmentIdTest extends TestCase
{

    public function testGenerate(): void
    {
        $establishmentId = EstablishmentId::generate();
        $this->assertNotNull($establishmentId);
    }

    public function testToString(): void
    {
        $establishmentId = EstablishmentId::generate();
        $this->assertIsString($establishmentId->toString());
    }

    public function testFromString(): void
    {
        $uuid = 'd10fec45-b26d-4592-8b3d-14433da59092';
        $establishmentId = EstablishmentId::fromString($uuid);
        $this->assertEquals($uuid, $establishmentId->toString());
    }
}
