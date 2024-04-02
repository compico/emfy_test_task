<?php

declare(strict_types=1);

namespace Queue\Client;

use Pheanstalk\Exception\DeadlineSoonException;
use Pheanstalk\Pheanstalk;
use Queue\Exception\ReserveException;
use Queue\Queue\CrmQueueInterface;
use Queue\Queue\DefaultQueueInterface;
use Queue\Task\TaskInterface;

use function json_decode;

class Beanstalk implements QueueInterface, CrmQueueInterface, DefaultQueueInterface
{
    public function __construct(
        protected readonly Pheanstalk $connection,
        protected readonly string $tube,
    ) {
        if ($this->tube !== QueueInterface::DEFAULT_TUBE) {
            $this->connection->useTube($this->tube);
            $this->connection->watch($this->tube);
            $this->connection->ignore(QueueInterface::DEFAULT_TUBE);
        }
    }

    public function getTube(): string
    {
        return $this->tube;
    }

    public function ignore(string $tubeName = QueueInterface::DEFAULT_TUBE): self
    {
        $this->connection->ignore($tubeName);

        return $this;
    }

    public function sendTask(TaskInterface $task): int
    {
        $job = $this->connection->put($task->toJson(), $task->getPriority());

        return $job->getId();
    }

    public function reserveWithTimeout(int $timeout = 10): TaskInterface
    {
        try {
            $job = $this->connection->reserveWithTimeout($timeout);
            if ($job === null) {
                throw new ReserveException('Empty job');
            }

            $json = $job->getData();

            $data = json_decode($json, true);

            /** @var TaskInterface $task */
            $task = (string)($data['task'] ?? '');
            if ($task === '') {
                throw new ReserveException('Unknown task');
            }

            $this->connection->delete($job);

            return $task::fromArray($data);
        } catch (DeadlineSoonException $e) {
            throw new ReserveException('Deadline exceeded', 0, $e);
        }
    }
}
