<?php

declare(strict_types=1);

namespace Crm\Factory;

use AmoCRM\Client\AmoCRMApiClient;
use App\Helper\ConfigHelper;
use Crm\Exception\AccountNotFoundException;
use Crm\Repository\AccountRepository;
use Crm\Service\ApiClient;
use Crm\ValueObject\AccessTokenOptions;
use Crm\ValueObject\OAuthConfig;
use League\OAuth2\Client\Token\AccessToken;

class ApiClientFactory
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly OAuthConfig $oAuthConfig,
        private readonly ConfigHelper $config,
    ) {
    }

    public function makeBaseClient(): ApiClient
    {
        $amoClient = new AmoCRMApiClient(
            $this->oAuthConfig->getIntegrationId(),
            $this->oAuthConfig->getSecretKey(),
            $this->oAuthConfig->getRedirectDomain(),
        );

        return new ApiClient($amoClient, $this->config);
    }

    public function makeClientByAccountId(int $accountId): ApiClient
    {
        $account = $this->accountRepository->getById($accountId);

        if ($account === null) {
            throw new AccountNotFoundException();
        }

        $amoClient = new AmoCRMApiClient(
            $this->oAuthConfig->getIntegrationId(),
            $this->oAuthConfig->getSecretKey(),
            $this->oAuthConfig->getRedirectDomain(),
        );

        $amoClient->setAccessToken(
            new AccessToken(
                (new AccessTokenOptions(
                    $account->getAccessToken(),
                    $account->getRefreshToken(),
                    $account->getExpiresIn(),
                ))->toAccessTokenOptions()
            )
        )->setAccountBaseDomain($account->getBaseDomain());

        return new ApiClient($amoClient, $this->config);
    }
}
