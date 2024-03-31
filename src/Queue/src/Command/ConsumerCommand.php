<?php

declare(strict_types=1);

namespace Queue\Command;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Queue\Client\Beanstalk;
use Queue\Client\QueueInterface;
use Queue\Exception\ReserveException;
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
        $this->queue = $this->container->get(Beanstalk::class);

        if ($this->queue instanceof Beanstalk) {
            $tubeName = $input->getOption('tube') ?? '';
            if ($tubeName === '') {
                throw new InvalidOptionException();
            }
            if ($tubeName !== QueueInterface::DEFAULT_TUBE) {
                $this->queue->watchTube($tubeName);
                $this->queue->ignore();
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while (true) {
            try {
                $task = $this->queue->reserveWithTimeout();
            } catch (ReserveException $e) {
                sleep(5);
                continue;
            }
            try {
                /** @var WorkerInterface $worker */
                $worker = $this->container->get($task->getWorkerClass());
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
