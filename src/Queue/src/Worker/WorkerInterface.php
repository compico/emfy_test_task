<?php

namespace Queue\Worker;

use Queue\Task\TaskInterface;

interface WorkerInterface
{
    public function handle(TaskInterface $task): void;
}