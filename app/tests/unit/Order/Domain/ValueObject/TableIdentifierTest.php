<?php

declare(strict_types=1);

namespace App\Tests\unit\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\TableIdentifier;
use App\Shared\ValueObjectInterface;
use PHPUnit\Framework\TestCase;

final class TableIdentifierTest extends TestCase
{
    public function testCreateTableIdentifier(): void
    {
        $tableIdentifier = TableIdentifier::fromString('TABLE05');
        $this->assertEquals('TABLE05', $tableIdentifier->toString());
    }

    public function testEquals(): void
    {
        $tableIdentifier = TableIdentifier::fromString('TABLE_AAA');
        $tableIdentifierTwo = TableIdentifier::fromString('TABLE_AAA');
        $this->assertTrue($tableIdentifier->equals($tableIdentifierTwo));
    }

    public function testEqualsWithDifferentClass(): void
    {
        $tableIdentifier = TableIdentifier::fromString('TABLE_BBB');
        $this->assertFalse($tableIdentifier->equals($this->createMock(ValueObjectInterface::class)));
    }
}
