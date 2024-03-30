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
use Queue\Client\ListenerInterface;

class AuthHandler implements RequestHandlerInterface
{
    protected LoggerInterface $logger;
    protected ListenerInterface $queue;

    public function __construct(LoggerInterface $logger, ListenerInterface $queue)
    {
        $this->logger = $logger;
        $this->queue  = $queue;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();

        if (!isset($params['code']) || empty($params['code'])) {
            $this->logger->error('empty code');
            return new EmptyResponse(StatusCodeInterface::STATUS_OK);
        }

        $this->queue->sendTask(new WidgetInstallTask(
            $params['code'],
        ));

        return new EmptyResponse(StatusCodeInterface::STATUS_OK);
    }
}
