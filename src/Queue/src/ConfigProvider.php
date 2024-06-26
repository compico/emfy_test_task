<?php

declare(strict_types=1);

namespace Queue;

use Pheanstalk\Pheanstalk;
use Queue\Client\Beanstalk;
use Queue\Client\QueueInterface;
use Queue\Command\ConsumerCommand;
use Queue\Factory\PheanstalkFactory;
use Queue\Queue\CrmQueueInterface;
use Queue\Queue\DefaultQueueInterface;

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
            'laminas-cli' => $this->getCommands(),
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
                Pheanstalk::class => PheanstalkFactory::class,
            ],
            'auto' => [
                'types' => [
                    CrmQueueInterface::class => [
                        'typeOf' => Beanstalk::class,
                        'parameters' => [
                            'connection' => Pheanstalk::class,
                            'tube' => QueueInterface::CRM_TUBE,
                        ],
                    ],
                    DefaultQueueInterface::class => [
                        'typeOf' => Beanstalk::class,
                        'parameters' => [
                            'connection' => Pheanstalk::class,
                            'tube' => QueueInterface::DEFAULT_TUBE,
                        ],
                    ],
                ],
            ],
        ];
    }

    public function getBeanstalkConfig(): array
    {
        return [
            'host' => '%env(QUEUE_HOST)%',
            'port' => '%env(QUEUE_PORT)%',
        ];
    }

    public function getCommands(): array
    {
        return [
            'commands' => [
                'system:consumer' => ConsumerCommand::class,
            ],
        ];
    }
}
