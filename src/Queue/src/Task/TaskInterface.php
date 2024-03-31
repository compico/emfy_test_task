<?php

declare(strict_types=1);

namespace Queue\Task;

use Queue\Worker\WorkerInterface;

interface TaskInterface
{
    public const HIGH_PRIORITY = 1;
    public const DEFAULT_PRIORITY = 1 << 10;
    public const LOW_PRIORITY = 1 << 11;
    public const WORKER_CLASS = '';

    public static function fromArray(array $data): self;

    public function toArray(): array;

    public function toJson(): string;

    public function getPriority(): int;

    public function getWorkerClass(): string;
}
