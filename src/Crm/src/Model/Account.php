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
    protected int $accountId;
    protected string $accessToken;
    protected string $refreshToken;
    protected Carbon $createdAt;
    protected Carbon $updatedAt;
    protected Carbon $deletedAt;

    public static function newAccountModel(int $accountId): self
    {
        $account = new Account();

        $account->accountId = $accountId;

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
                'access_token' => $this->getAccessToken(),
                'refresh_token' => $this->getRefreshToken(),
            ]
        );

        return $this;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): Carbon
    {
        return $this->deletedAt;
    }
}
