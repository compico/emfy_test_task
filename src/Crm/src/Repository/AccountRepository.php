<?php

namespace Crm\Repository;

use Crm\Model\Account;

class AccountRepository
{
    public function getById(int $accountId): ?Account
    {
        return Account::getQuery()
            ->where('account_id', $accountId)
            ->first();
    }

    public function newAccount(Account $model): void
    {
        Account::newAccountModel($model->getAccountId(), $model->getBaseDomain())
            ->setAccessToken($model->getAccessToken())
            ->setRefreshToken($model->getRefreshToken())
            ->setExpiresIn($model->getExpiresIn())
            ->updateOrCreate();
    }
}
