<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEntryCommand extends Command
{
    protected static $defaultName = 'app:create-entry';

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
            ->setDescription('Creates a new entry.')
            ->setHelp('This command allows you to create a entry...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Entry Creator',
            '============',
        ]);

        $qb = $this->conn->createQueryBuilder();

        $qb
            ->insert('cdek')
            ->setValue('id', ':id')
            ->setValue('payload', ':payload')
            ->setParameter('id', 1, \PDO::PARAM_INT)
            ->setParameter('payload', 'new_payload');

        $qb->execute();

        $output->writeln('Entry successfully added!');
    }
}