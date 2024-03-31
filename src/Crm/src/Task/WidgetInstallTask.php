<?php

namespace Crm\Task;

use Crm\Worker\WidgetInstallWorker;
use Queue\Task\TaskInterface;
use Queue\Trait\TaskTrait;

class WidgetInstallTask implements TaskInterface
{
    use TaskTrait;

    protected string $code;
    public const WORKER_CLASS = WidgetInstallWorker::class;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function fromArray(array $data): self
    {
        return new WidgetInstallTask(
            (string)($data['code'] ?? '')
        );
    }

    public function toArray(): array
    {
        return [
            'task' => self::class,
            'code' => $this->code,
        ];
    }

    public function getPriority(): int
    {
        return self::HIGH_PRIORITY;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}