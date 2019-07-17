<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateTableCommand
 * @package App\Command
 */
class CreateTableCommand extends Command
{
    protected static $defaultName = 'app:create-table';

    /** @var Connection  */
    private $conn;

    /**
     * CreateEntryCommand constructor.
     * @param ContainerInterface $container
     * @throws \Exception
     */
    public function __construct(ContainerInterface $container)
    {
        $this->conn = $container->get('doctrine.dbal.clickhouse_connection');

        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new table.')
            ->setHelp('This command allows you to create a table...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Table Creator',
            '============',
        ]);

        $fromSchema = $this->conn->getSchemaManager()->createSchema();
        $toSchema = clone $fromSchema;

        $newTable = $toSchema->createTable('cdek');

        $newTable->addColumn('id', 'integer', ['unsigned' => true]);
        $newTable->addColumn('payload', 'string', ['notnull' => true]);
        $newTable->addColumn('event_date', 'date', ['default' => 'toDate(now())']);
        $newTable->setPrimaryKey(['id']);

        $sqlArray = $fromSchema->getMigrateToSql($toSchema, $this->conn->getDatabasePlatform());
        foreach ($sqlArray as $sql) {
            $this->conn->exec($sql);
        }

        $output->writeln('Table successfully created!');
    }
}
