<?php

declare(strict_types=1);

namespace Crm\Handler;

use Crm\Task\WidgetInstallTask;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Queue\Queue\CrmQueueInterface;

class AuthHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CrmQueueInterface $queue,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();

        if (!isset($params['code']) || !isset($params['referer'])) {
            $this->logger->error('empty code or referer');
            return new EmptyResponse(StatusCodeInterface::STATUS_OK);
        }

        $this->queue->sendTask(new WidgetInstallTask(
            $params['code'],
            $params['referer'],
        ));

        return new EmptyResponse(StatusCodeInterface::STATUS_OK);
    }
}
