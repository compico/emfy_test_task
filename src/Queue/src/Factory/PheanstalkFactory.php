<?php

declare(strict_types=1);

namespace Queue\Factory;

use App\Exception\EmptyConfigException;
use Pheanstalk\Pheanstalk;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Queue\Client\Beanstalk;

class PheanstalkFactory
{
    /**
     * @throws EmptyConfigException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): Pheanstalk
    {
        /** @var array $config */
        $config = $container->get('config')['beanstalk'] ?? [];

        if ($config === null) {
            throw new EmptyConfigException('Beanstalk config is empty');
        }

        $host = (string)($config['host'] ?? 'localhost');
        $port = (int)($config['port'] ?? 33100);

        if ($host === '' || $port === 0) {
            throw new EmptyConfigException('Beanstalk config is empty');
        }

        return Pheanstalk::create(
                $host,
                $port
        );
    }
}
