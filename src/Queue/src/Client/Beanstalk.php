<?php

declare(strict_types=1);

namespace Queue\Client;

use Pheanstalk\Exception\DeadlineSoonException;
use Pheanstalk\Pheanstalk;
use Queue\Exception\ReserveException;
use Queue\Task\TaskInterface;

use function json_decode;

class Beanstalk implements QueueInterface
{
    protected Pheanstalk $connection;
    public function __construct(
        Pheanstalk $pheanstalk,
    ) {
        $this->connection = $pheanstalk;
    }

    public function useTube(string $tubeName = QueueInterface::DEFAULT_TUBE): self
    {
        $this->connection->useTube($tubeName);

        return $this;
    }

    public function watchTube(string $tubeName = QueueInterface::DEFAULT_TUBE): self
    {
        $this->connection->watch($tubeName);

        return $this;
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
