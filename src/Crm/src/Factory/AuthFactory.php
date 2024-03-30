<?php

declare(strict_types=1);

namespace Crm\Factory;

use Crm\Handler\AuthHandler;
use Psr\Container\ContainerInterface;
use Queue\Client\Beanstalk;
use Queue\Client\ListenerInterface;

class AuthFactory
{
    public function __invoke(ContainerInterface $container): AuthHandler
    {
        /** @var Beanstalk $bs */
        $bs = $container->get(Beanstalk::class);
        $bs->useTube(ListenerInterface::CRM_TUBE);

        return new AuthHandler(
            $bs
        );
    }
}
