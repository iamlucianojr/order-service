<?php

declare(strict_types=1);

namespace App\Tests\unit\Order\Domain\ValueObject;

use App\Order\Domain\ValueObject\TableIdentifier;
use PHPUnit\Framework\TestCase;

final class TableIdentifierTest extends TestCase
{
    public function testCreateTableIdentifier(): void
    {
        $tableIdentifier = TableIdentifier::fromString('TABLE05');
        $this->assertEquals('TABLE05', $tableIdentifier->toString());
    }
}
