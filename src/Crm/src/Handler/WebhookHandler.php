<?php

declare(strict_types=1);

namespace Crm\Handler;

use Crm\Task\LeadUpdateTask;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Queue\Queue\CrmQueueInterface;

use function array_keys;
use function reset;
use function sprintf;

class WebhookHandler implements RequestHandlerInterface
{
    private const ENTITY_LEAD = 'leads';
    private const ENTITY_CONTACT = 'contacts';
    private const ENTITY_ACCOUNT = 'account';
    private const ACTION_ADD = 'add';
    private const ACTION_UPDATE = 'update';

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CrmQueueInterface $queue,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        if ($data === null) {
            $this->logger->error('empty data');
            return new EmptyResponse(StatusCodeInterface::STATUS_OK);
        }

        $this->logger->info('new hook', $data);

        $accountId = 0;
        $action = '';
        $entityType = '';
        $entityData = [];

        foreach ($data as $type => $entity) {
            switch ($type) {
                case self::ENTITY_ACCOUNT:
                    if (!isset($entity['id'])) {
                        $this->logger->error('empty account_id');
                        return new EmptyResponse(StatusCodeInterface::STATUS_OK);
                    }
                    $accountId = (int)$entity['id'];
                    if ($accountId < 1) {
                        $this->logger->error('invalid account_id');
                        return new EmptyResponse(StatusCodeInterface::STATUS_OK);
                    }
                    break;
                case self::ENTITY_LEAD:
                case self::ENTITY_CONTACT:
                    $actions = array_keys($entity);
                    $action = reset($actions);
                    $entityType = $type;
                    $entityData = reset($entity[$action]);
                    break;
                default:
                    $this->logger->error(
                        sprintf(
                            'Unsupported type %s',
                            $type
                        )
                    );
                    return new EmptyResponse(StatusCodeInterface::STATUS_OK);
            }
        }

        if ($entityType === self::ENTITY_LEAD) {
            if ($action === self::ACTION_ADD) {
            } elseif ($action === self::ACTION_UPDATE) {
            }
        } elseif ($entityType === self::ENTITY_CONTACT) {
            if ($action === self::ACTION_ADD) {
            } elseif ($action === self::ACTION_UPDATE) {
            }
        }

        $task = new LeadUpdateTask(
            $accountId,
            $lead->getPipelineId(),
            $lead->getId()
        );

        $jobId = $this->queue->sendTask($task);

        $this->logger->info('sent new task', ['job_id' => $jobId, 'task' => $task->toArray()]);

        return new EmptyResponse(StatusCodeInterface::STATUS_OK);
    }
}
