<?php

declare(strict_types=1);

namespace Crm\Handler;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class AuthHandler implements RequestHandlerInterface
{
    protected ConsoleLogger $logger;

    public function __construct(ConsoleLogger $logger)
    {
        $this->logger = $logger;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $this->logger->info('New message: ', [$request->getQueryParams()]);

        return new EmptyResponse(StatusCodeInterface::STATUS_OK);
    }
}
