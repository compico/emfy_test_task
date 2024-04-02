<?php

declare(strict_types=1);

namespace Queue\Command;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Queue\Client\QueueInterface;
use Queue\Exception\ReserveException;
use Queue\Queue\CrmQueueInterface;
use Queue\Queue\DefaultQueueInterface;
use Queue\Worker\WorkerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function sleep;
use function sprintf;

class ConsumerCommand extends Command
{
    protected ContainerInterface $container;
    protected LoggerInterface $logger;
    protected QueueInterface $queue;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure(): void
    {
        $this->addOption(
            'tube',
            't',
            InputArgument::OPTIONAL,
            'Tube name',
            QueueInterface::DEFAULT_TUBE
        );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->logger = $this->container->get(LoggerInterface::class);

        $tubeName = $input->getOption('tube') ?? '';
        if ($tubeName === '') {
            throw new InvalidOptionException();
        }

        switch ($tubeName) {
            case QueueInterface::DEFAULT_TUBE:
                $this->queue = $this->container->get(DefaultQueueInterface::class);
                break;
            case QueueInterface::CRM_TUBE:
                $this->queue = $this->container->get(CrmQueueInterface::class);
                break;
            default:
                throw new InvalidOptionException();
        }
        $this->logger->info('Consumer started for tube ' . $tubeName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while (true) {
            try {
                $task = $this->queue->reserveWithTimeout(10);
                $this->logger->info('new task' . $task->toJson() ?? 'empty');
            } catch (ReserveException $e) {
                sleep(5);
                continue;
            }
            try {
                /** @var WorkerInterface $worker */
                $worker = $this->container->get($task->getWorkerClass());
                $this->logger->info('new task for ' . $task->getWorkerClass());
                $worker->handle($task);
            } catch (NotFoundExceptionInterface $e) {
                $this->logger->warning(
                    sprintf(
                        'Worker %s not found!',
                        $task->getWorkerClass()
                    )
                );
                continue;
            }
        }
    }
}
