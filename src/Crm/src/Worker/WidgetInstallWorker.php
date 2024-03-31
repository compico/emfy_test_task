<?php

declare(strict_types=1);

namespace Crm\Worker;

use Crm\Task\WidgetInstallTask;
use Psr\Log\LoggerInterface;
use Queue\Task\TaskInterface;
use Queue\Worker\WorkerInterface;

class WidgetInstallWorker implements WorkerInterface
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param WidgetInstallTask $task
     */
    public function handle(TaskInterface $task): void
    {
        $this->logger->info('new task ' . $task->getCode());
    }
}
