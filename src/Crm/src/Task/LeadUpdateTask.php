<?php

declare(strict_types=1);

namespace Crm\Task;

use Crm\Worker\LeadUpdateWorker;
use Queue\Task\TaskInterface;
use Queue\Trait\TaskTrait;

class LeadUpdateTask implements TaskInterface
{
    use TaskTrait;

    public const WORKER_CLASS = LeadUpdateWorker::class;

    public function __construct(
        private readonly int $accountId,
        private readonly int $pipelineId,
        private readonly int $leadId,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new LeadUpdateTask(
            (int)($data['account_id'] ?? 0),
            (int)($data['pipeline_id'] ?? 0),
            (int)($data['lead_id'] ?? 0)
        );
    }

    public function toArray(): array
    {
        return [
            'task' => self::class,
            'account_id' => $this->accountId,
            'pipeline_id' => $this->pipelineId,
            'lead_id' => $this->leadId,
        ];
    }

    public function getPriority(): int
    {
        return TaskInterface::HIGH_PRIORITY;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getPipelineId(): int
    {
        return $this->pipelineId;
    }

    public function getLeadId(): int
    {
        return $this->leadId;
    }
}
