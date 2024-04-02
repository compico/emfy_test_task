<?php

declare(strict_types=1);

namespace Crm\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /** @var string */
    protected $table = 'account';

    /** @var string */
    protected $primaryKey = 'account_id';
    /** @var bool */
    public $incrementing = false;

    protected $fillable = [
        'account_id',
        'base_domain',
        'access_token',
        'refresh_token',
        'expires_in',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function newAccountModel(int $accountId, string $baseDomain): self
    {
        $account = new Account();

        $account->setAttribute('account_id', $accountId);
        $account->setAttribute('base_domain', $baseDomain);

        return $account;
    }

    public static function getQuery(): Builder
    {
        $model = new Account();

        $query = $model->registerGlobalScopes($model->newQueryWithoutScopes());
        $query->setModel(new Account());

        return $query;
    }

    public function updateOrCreate(): self
    {
        $query = self::newQuery();

        $query->updateOrCreate(
            ['account_id' => $this->getAccountId()],
            [
                'base_domain' => $this->getBaseDomain(),
                'access_token' => $this->getAccessToken(),
                'refresh_token' => $this->getRefreshToken(),
                'expires_in' => $this->getExpiresIn(),
            ]
        );

        return $this;
    }

    public function getAccountId(): int
    {
        return (int)$this->getAttributeFromArray('account_id');
    }

    public function getBaseDomain(): string
    {
        return (string)$this->getAttributeFromArray('base_domain');
    }

    public function getAccessToken(): string
    {
        return (string)$this->getAttributeFromArray('access_token');
    }

    public function setAccessToken(string $accessToken): self
    {
        return $this->setAttribute('access_token', $accessToken);
    }

    public function getRefreshToken(): string
    {
        return (string)$this->getAttributeFromArray('refresh_token');
    }

    public function setRefreshToken(string $refreshToken): self
    {
        return $this->setAttribute('refresh_token', $refreshToken);
    }

    public function getExpiresIn(): int
    {
        return (int)$this->getAttributeFromArray('expires_in');
    }

    public function setExpiresIn(int $expiresIn): self
    {
        return $this->setAttribute('expires_in', $expiresIn);
    }

    public function getCreatedAt(): Carbon
    {
        return $this->getAttributeFromArray('created_at');
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->getAttributeFromArray('updated_at');
    }

    public function getDeletedAt(): ?Carbon
    {
        return $this->getAttributeFromArray('deleted_at');
    }
}
