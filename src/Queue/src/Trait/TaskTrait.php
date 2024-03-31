<?php

declare(strict_types=1);

namespace Queue\Trait;

use App\Exception\CastException;
use InvalidArgumentException;
use Queue\Task\TaskInterface;

use function json_encode;

trait TaskTrait
{
    protected string $tubeName;

    public function getWorkerClass(): string
    {
        return self::WORKER_CLASS;
    }

    public function toJson(): string
    {
        if ($this instanceof TaskInterface) {
            $data = json_encode($this->toArray());
        } else {
            throw new InvalidArgumentException();
        }

        if (!$data) {
            throw new CastException();
        }

        return $data;
    }
}
