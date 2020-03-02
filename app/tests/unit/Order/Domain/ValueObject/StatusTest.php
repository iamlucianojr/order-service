<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\Status;
use PHPUnit\Framework\TestCase;

final class StatusTest extends TestCase
{
    public function testDelivered(): void
    {
        $status = Status::fromString('delivered');
        $this->assertEquals(Status::delivered(), $status->toString());
    }

    public function testCanceled(): void
    {
        $status = Status::fromString(Status::CANCELED);
        $this->assertEquals(Status::canceled(), $status->toString());
    }

    public function testWaiting(): void
    {
        $status = Status::fromString(Status::WAITING);
        $this->assertEquals(Status::waiting(), $status->toString());
    }
}
