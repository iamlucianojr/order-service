<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Persistence\ORM\Projections;

use Doctrine\DBAL\Connection;
use PDO;
use function sprintf;
use stdClass;

final class OrderFinder implements OrderFinderInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->connection->setFetchMode(PDO::FETCH_OBJ);
    }

    public function findAll(): array
    {
        return $this->connection->fetchAll(sprintf('SELECT * FROM %s', Table::ORDER));
    }

    public function findByUuid(string $orderId): ?stdClass
    {
        $stmt = $this->connection->prepare(sprintf('SELECT * FROM %s WHERE order_id = :order_id', Table::ORDER));
        $stmt->bindValue('order_id', $orderId);
        $stmt->execute();

        $result = $stmt->fetch();

        if (false === $result) {
            return null;
        }

        return $result;
    }
}
