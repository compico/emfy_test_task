<?php

declare(strict_types=1);

namespace Crm\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /** @var string[] */
    protected $fillable = [
        'lead_id',
        'account_id',
        'name',
        'status_id',
        'price',
        'responsible_user_id',
        'pipeline_id',
        'last_modified',
        'created_at',
        'updated_at',
    ];

    public static function fromArray(array $data): self
    {
        $lead = new Lead();

        $lead->setLeadId((int)$data['lead_id']);
        $lead->setAccountId((int)$data['account_id']);
        $lead->setName((string)$data['name']);
        $lead->setStatusId((int)$data['status_id']);
        $lead->setPrice((int)$data['price']);
        $lead->setResponsibleUserId((int)$data['responsible_user_id']);
        $lead->setPipelineId((int)$data['pipeline_id']);
        $lead->setLastModified((int)$data['last_modified']);

        return $lead;
    }

    public function toArray(): array
    {
        return [
            'lead_id' => $this->getLeadId(),
            'account_id' => $this->getAccountId(),
            'name' => $this->getName(),
            'status_id' => $this->getStatusId(),
            'price' => $this->getPrice(),
            'responsible_user_id' => $this->getResponsibleUserId(),
            'pipeline_id' => $this->getPipelineId(),
            'last_modified' => $this->getLastModified(),
        ];
    }

    public static function getQuery(): Builder
    {
        $model = new Lead();

        $query = $model->registerGlobalScopes($model->newQueryWithoutScopes());
        $query->setModel(new Lead());

        return $query;
    }

    public function getLeadId(): int
    {
        return (int)$this->getAttributeFromArray('lead_id');
    }

    public function getAccountId(): int
    {
        return (int)$this->getAttributeFromArray('account_id');
    }

    public function getName(): string
    {
        return (string)$this->getAttributeFromArray('name');
    }

    public function getStatusId(): int
    {
        return (int)$this->getAttributeFromArray('status_id');
    }

    public function getPrice(): int
    {
        return (int)$this->getAttributeFromArray('price');
    }

    public function getResponsibleUserId(): int
    {
        return (int)$this->getAttributeFromArray('responsible_user_id');
    }

    public function getLastModified(): int
    {
        return (int)$this->getAttributeFromArray('last_modified');
    }

    public function getPipelineId(): int
    {
        return (int)$this->getAttributeFromArray('pipeline_id');
    }

    public function setLeadId(int $value): self
    {
        $this->setAttribute('id', $value);

        return $this;
    }

    public function setAccountId(int $value): self
    {
        $this->setAttribute('account_id', $value);

        return $this;
    }

    public function setName(string $value): self
    {
        $this->setAttribute('name', $value);

        return $this;
    }

    public function setStatusId(int $value): self
    {
        $this->setAttribute('status_id', $value);

        return $this;
    }

    public function setPrice(int $value): self
    {
        $this->setAttribute('price', $value);

        return $this;
    }

    public function setResponsibleUserId(int $value): self
    {
        $this->setAttribute('responsible_user_id', $value);

        return $this;
    }

    public function setLastModified(int $value): self
    {
        $this->setAttribute('last_modified', $value);

        return $this;
    }

    public function setPipelineId(int $value): self
    {
        $this->setAttribute('pipeline_id', $value);

        return $this;
    }
}
