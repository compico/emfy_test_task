<?php

declare(strict_types=1);

namespace Queue\Task;

interface TaskInterface
{
    public const HIGH_PRIORITY   = 1;
    public const MEDIUM_PRIORITY = 1 << 10;
    public const LOW_PRIORITY    = 1 << 11;

    public function fromArray(array $data): self;

    public function toArray(): array;

    public function toJson(): string;

    public function getPriority(): int;
}
