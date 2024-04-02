<?php

declare(strict_types=1);

namespace Crm\Service;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Filters\CustomFieldsFilter;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFields\TextCustomFieldModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\WebhookModel;
use App\Helper\ConfigHelper;
use Crm\ValueObject\AccessTokenOptions;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

class ApiClient
{
    public const TZ_CF_CODE = 'TZ_CF';

    public function __construct(
        private readonly AmoCRMApiClient $client,
        private readonly ConfigHelper $config,
    ) {
    }

    public function exchangeCodeAndSetToken(string $baseDomain, string $code): AccessTokenInterface
    {
        $this->client->setAccountBaseDomain($baseDomain);

        $token = $this->client->getOAuthClient()->getAccessTokenByCode($code);

        $this->client->setAccessToken(
            new AccessToken(
                (new AccessTokenOptions(
                    $token->getToken(),
                    $token->getRefreshToken(),
                    $token->getExpires()
                ))->toAccessTokenOptions()
            )
        );

        return $token;
    }

    public function getAccountId(): int
    {
        return $this->client->account()->getCurrent()->getId();
    }

    public function subscribeToWebhook(): void
    {
        // "Сделка добавлена", "Контакт добавлен", "Сделка изменена", "Контакт изменен"
        $entities = ['add_lead', 'add_contact', 'update_lead', 'update_contact'];

        $this->client->webhooks()->subscribe(
            (new WebhookModel())
                ->setDestination($this->config->getWebhookRoute())
                ->setSettings($entities)
        );
    }

    public function getLeadByIdAndPipelineId(int $pipelineId, int $leadId): LeadModel
    {
        return $this->client->leads()->get(
            (new LeadsFilter())
                ->setPipelineIds([$pipelineId])
                ->setIds([$leadId])
        )->first();
    }

    public function createTestTaskCustomField(): void
    {
        $tzCf = $this->getTestTaskCustomField();
        if ($tzCf !== null) {
            return;
        }

        $cf = new TextCustomFieldModel();
        $cf->setEntityType(EntityTypesInterface::LEADS)
            ->setName('Поле тестового задания')
            ->setCode(self::TZ_CF_CODE)
            ->setIsApiOnly(true);

        $this->client->customFields(EntityTypesInterface::LEADS)->addOne($cf);
    }

    public function getTestTaskCustomField(): ?TextCustomFieldModel
    {
        try {
            $cfCollection = $this->client->customFields(EntityTypesInterface::LEADS)->get(
                (new CustomFieldsFilter())->setTypes([CustomFieldModel::TYPE_TEXT])
            );
            return $cfCollection->getBy('code', self::TZ_CF_CODE);
        } catch (AmoCRMApiNoContentException) {
            return null;
        }
    }
}
