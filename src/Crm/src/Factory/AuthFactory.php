<?php

declare(strict_types=1);

namespace Crm\Factory;

use Crm\Handler\AuthHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Queue\Client\Beanstalk;
use Queue\Client\QueueInterface;

class AuthFactory
{
    public function __invoke(ContainerInterface $container): AuthHandler
    {
        /** @var Beanstalk $bs */
        $bs = $container->get(Beanstalk::class);
        $bs->useTube(QueueInterface::CRM_TUBE);

        return new AuthHandler(
            $container->get(LoggerInterface::class),
            $bs
        );
    }
}
