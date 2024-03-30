<?php

declare(strict_types=1);

namespace Queue\Client;

use Queue\Task\TaskInterface;

interface ListenerInterface
{
    public const DEFAULT_TUBE = 'default';
    public const CRM_TUBE     = 'crm';

    public function sendTask(TaskInterface $task): int;
}
