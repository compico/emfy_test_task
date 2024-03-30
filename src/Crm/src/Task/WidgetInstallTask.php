<?php

namespace Crm\Task;

use Queue\Client\ListenerInterface;
use Queue\Task\TaskInterface;
use Queue\Trait\TaskTrait;

class WidgetInstallTask implements TaskInterface
{
    use TaskTrait;

    protected string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function fromArray(array $data): self
    {
        return new WidgetInstallTask(
            $data['code']
        );
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
        ];
    }

    public function getPriority(): int
    {
        return self::HIGH_PRIORITY;
    }
}