<?php

declare(strict_types=1);

namespace Crm\Worker;

use Crm\Factory\ApiClientFactory;
use Crm\Model\Account;
use Crm\Repository\AccountRepository;
use Crm\Task\WidgetInstallTask;
use Psr\Log\LoggerInterface;
use Queue\Task\TaskInterface;
use Queue\Worker\WorkerInterface;

class WidgetInstallWorker implements WorkerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly AccountRepository $repository,
        private readonly ApiClientFactory $apiClientFactory,
    ) {
    }

    /**
     * @param WidgetInstallTask $task
     */
    public function handle(TaskInterface $task): void
    {
        $this->logger->info('new install ' . $task->getBaseDomain());

        $client = $this->apiClientFactory->makeBaseClient();

        $token = $client->exchangeCodeAndSetToken($task->getBaseDomain(), $task->getCode());
        $accountId = $client->getAccountId();

        $account = Account::newAccountModel(
            $accountId,
            $task->getBaseDomain()
        )->setAccessToken($token->getToken())
            ->setRefreshToken($token->getRefreshToken())
            ->setExpiresIn($token->getExpires());

        $this->repository->newAccount($account);

        // Такое дело если подписываться руками - будет ошибка
        // 'validation-errors' =>
        // 'path' => 'destination',
        // 'detail' => 'The host could not be resolved.',
        // Помню с архитектом общался на эту тему, как это можно пофиксить, но решили не трогать
        // Тут скорее бага serveo, которую я активно использую
        // Валидация проходит на уровне DNS записей, которые не находит из-за чего падает ошибка
        // Но через фронт добавление спокойно происходит
        // Забавно, когда повередение одной функции меняется в зависимости от того, фронт это или api
        // $client->subscribeToWebhook();

        $client->createTestTaskCustomField();

        $this->logger->info(
            sprintf(
                'install completed, accountId: %d, baseDomain: %s',
                $accountId,
                $task->getBaseDomain()
            )
        );
    }
}
