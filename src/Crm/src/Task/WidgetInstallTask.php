<?php

declare(strict_types=1);

namespace Crm\Task;

use Crm\Worker\WidgetInstallWorker;
use Queue\Task\TaskInterface;
use Queue\Trait\TaskTrait;

class WidgetInstallTask implements TaskInterface
{
    use TaskTrait;

    public const WORKER_CLASS = WidgetInstallWorker::class;

    public function __construct(
        private readonly string $code,
        private readonly string $baseDomain,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new WidgetInstallTask(
            (string)($data['code'] ?? ''),
            (string)($data['base_domain'] ?? ''),
        );
    }

    public function toArray(): array
    {
        return [
            'task' => self::class,
            'base_domain' => $this->baseDomain,
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

    public function getBaseDomain(): string
    {
        return $this->baseDomain;
    }
}
