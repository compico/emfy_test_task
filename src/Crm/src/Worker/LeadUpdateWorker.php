<?php

namespace Crm\Worker;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use Crm\Factory\ApiClientFactory;
use Crm\Task\LeadUpdateTask;
use Psr\Log\LoggerInterface;
use Queue\Task\TaskInterface;
use Queue\Worker\WorkerInterface;

class LeadUpdateWorker implements WorkerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ApiClientFactory $apiClientFactory,
    ) {
    }

    /**
     * @param LeadUpdateTask $task
     */
    public function handle(TaskInterface $task): void
    {
        $this->logger->info('New task lead_update', $task->toArray());

        $client = $this->apiClientFactory->makeClientByAccountId($task->getAccountId());
        $lead = $client->getLeadByIdAndPipelineId($task->getPipelineId(), $task->getLeadId());
        $cf = $client->getTestTaskCustomField();

        // Если получен хук на создание карточки,
        // то текстовое примечание должно содержать:
        //      название сделки/контакта,
        //      ответственного
        //      время добавления карточки.
        // Если получен хук на изменение карточки,
        // то текстовое примечание должно содержать:
        //      названия и новые значения измененных полей,
        //      время изменения карточки
        $cfValue = new TextCustomFieldValuesModel();
        $cfValue->setFieldId($cf->getId());
        $cfValue->setValues(
            (new TextCustomFieldValueCollection())->add(
                (new TextCustomFieldValuesModel())->setValue()
            )
        );
//        $lead->setCustomFieldsValues((new CustomFieldsValuesCollection()->add());
    }
}
