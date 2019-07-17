<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetEntryCommand extends Command
{
    protected static $defaultName = 'app:get-entry';

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

    protected function configure()
    {
        $this
            ->setDescription('Get a new entry.')
            ->setHelp('This command allows you to get a entry...');
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
            'Entry Getter',
            '============',
        ]);

        $stmt = $this->conn->prepare('SELECT id, payload, event_date FROM cdek WHERE id = :id');

        $stmt->bindValue('id', 1, \PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            echo sprintf("Id: %d\nPayload: %s\nEvent date: %s\n", $row['id'], $row['payload'], $row['event_date']);
        }
    }
}
