<?php

declare(strict_types=1);

namespace Queue\Trait;

use App\Exception\CastException;
use InvalidArgumentException;
use Queue\Task\TaskInterface;

use function json_encode;

trait TaskTrait
{
    protected string $workerClass;
    protected string $tubeName;

    public function getWorkerClass(): string
    {
        return $this->workerClass;
    }

    public function setWorkerClass(string $class): self
    {
        $this->workerClass = $class;

        return $this;
    }

    public function getTubeName(): string
    {
        return $this->tubeName;
    }

    public function setTubeName(string $tubeName): self
    {
        $this->tubeName = $tubeName;

        return $this;
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
