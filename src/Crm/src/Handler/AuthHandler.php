<?php

declare(strict_types=1);

namespace Crm\Handler;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Queue\Client\ListenerInterface;

class AuthHandler implements RequestHandlerInterface
{
    protected ListenerInterface $queue;

    public function __construct(ListenerInterface $queue)
    {
        $this->queue = $queue;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new EmptyResponse(StatusCodeInterface::STATUS_OK);
    }
}
