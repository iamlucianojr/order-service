<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateProophDatabaseCommand extends Command
{
    private const CREATE_EVENT_STREAM_TABLE_QUERY = '
CREATE TABLE event_streams (
  no BIGSERIAL,
  real_stream_name VARCHAR(150) NOT NULL,
  stream_name CHAR(41) NOT NULL,
  metadata JSONB,
  category VARCHAR(150),
  PRIMARY KEY (no),
  UNIQUE (stream_name)
);
';

    private const CREATE_PROJECTION_TABLE_QUERY = '
CREATE TABLE projections (
  no BIGSERIAL,
  name VARCHAR(150) NOT NULL,
  position JSONB,
  state JSONB,
  status VARCHAR(28) NOT NULL,
  locked_until CHAR(26),
  PRIMARY KEY (no),
  UNIQUE (name)
);
';

    private const CREATE_CATEGORY_INDEX_QUERY = 'CREATE INDEX on event_streams (category);';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('prooph:create:database')
            ->setDescription('Create database for prooph.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->connection;
        $connection->beginTransaction();
        $connection->query(self::CREATE_EVENT_STREAM_TABLE_QUERY);
        $connection->query(self::CREATE_CATEGORY_INDEX_QUERY);
        $connection->query(self::CREATE_PROJECTION_TABLE_QUERY);
        $connection->commit();

        return 0;
    }
}
