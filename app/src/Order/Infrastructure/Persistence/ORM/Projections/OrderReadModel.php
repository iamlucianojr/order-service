<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Persistence\ORM\Projections;

use Doctrine\DBAL\Connection;
use Prooph\EventStore\Projection\AbstractReadModel;

final class OrderReadModel extends AbstractReadModel
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function init(): void
    {
        $tableName = Table::ORDER;

        $sql = <<<EOT
CREATE TABLE $tableName (
  order_id varchar NOT NULL,
  establishment_id varchar NOT NULL,
  catalog_flow_id varchar NOT NULL,
  catalog_flow_version BIGSERIAL NOT NULL,
  table_identifier varchar NOT NULL,
  items JSON NOT NULL,
  status varchar NOT NULL,
  PRIMARY KEY (order_id)
);
EOT;
        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    public function isInitialized(): bool
    {
        $tableName = Table::ORDER;

        $sql = "SELECT * FROM pg_catalog.pg_tables WHERE tablename like '$tableName';";

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $result = $statement->fetch();

        if (false === $result) {
            return false;
        }

        return true;
    }

    public function reset(): void
    {
        $tableName = Table::ORDER;

        $sql = "TRUNCATE TABLE $tableName;";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    public function delete(): void
    {
        $tableName = Table::ORDER;

        $sql = "DROP TABLE $tableName;";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    protected function insert(array $data): void
    {
        $this->connection->insert(Table::ORDER, $data);
    }

    protected function update(array $data, array $identifier): void
    {
        $this->connection->update(
            Table::ORDER,
            $data,
            $identifier
        );
    }
}
