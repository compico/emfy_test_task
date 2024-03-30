<?php

declare(strict_types=1);

namespace Queue\Client;

use Pheanstalk\Pheanstalk;
use Queue\Task\TaskInterface;

class Beanstalk implements ListenerInterface
{
    protected Pheanstalk $connection;
    public function __construct(
        Pheanstalk $pheanstalk,
    ) {
        $this->connection = $pheanstalk;
    }

    public function useTube(string $tubeName = ListenerInterface::DEFAULT_TUBE): self
    {
        $this->connection->useTube($tubeName);

        return $this;
    }

    public function sendTask(TaskInterface $task): int
    {
        $job = $this->connection->put($task->toJson(), $task->getPriority());

        return $job->getId();
    }
}
