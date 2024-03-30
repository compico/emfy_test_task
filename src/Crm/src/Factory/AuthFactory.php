<?php

declare(strict_types=1);

namespace Crm\Factory;

use Crm\Handler\AuthHandler;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;

class AuthFactory
{
    public function __invoke(ContainerInterface $container) : AuthHandler
    {
        return new AuthHandler(
            new ConsoleLogger(new ConsoleOutput())
        );
    }
}
