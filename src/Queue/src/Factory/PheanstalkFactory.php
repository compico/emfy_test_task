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
    public function __invoke(ContainerInterface $container): Beanstalk
    {
        /** @var array $config */
        $config = $container->get('config')['beanstalk'] ?? [];

        if (empty($config)) {
            throw new EmptyConfigException('Beanstalk config is empty');
        }

        $host = $config['host'] ?? '';
        $port = (int) ($config['port'] ?? 0);

        if (empty($host) || empty($port)) {
            throw new EmptyConfigException('Beanstalk config is empty');
        }

        return new Beanstalk(
            Pheanstalk::create(
                $host,
                $port
            )
        );
    }
}
