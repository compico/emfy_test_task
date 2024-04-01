<?php

namespace Crm\Repository;

use Crm\Model\Account;

class AccountRepository
{
    public function getById(int $accountId): ?Account
    {
        return Account::newQuery()
            ->where('account_id', $accountId)
            ->first();
    }

    public function newAccount(int $accountId, string $accessToken, string $refreshToken): void
    {
        Account::newAccountModel($accountId)
            ->setAccessToken($accessToken)
            ->setRefreshToken($refreshToken)
            ->updateOrCreate();
    }
}
