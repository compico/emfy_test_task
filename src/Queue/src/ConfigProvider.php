<?php

declare(strict_types=1);

namespace Queue;

use Pheanstalk\Connection;
use Pheanstalk\Contract\SocketFactoryInterface;
use Pheanstalk\SocketFactory;
use Queue\Client\Beanstalk;
use Queue\Factory\PheanstalkFactory;

/**
 * The configuration provider for the Queue module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'beanstalk' => $this->getBeanstalkConfig(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [],
            'factories' => [
                Beanstalk::class => PheanstalkFactory::class,
            ],
            'preferences' => [],
        ];
    }

    public function getBeanstalkConfig(): array
    {
        return [
            'host' => '%env(QUEUE_HOST)%',
            'port' => '%env(QUEUE_PORT)%',
        ];
    }
}
