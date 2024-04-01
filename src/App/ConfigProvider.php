<?php

declare(strict_types=1);

namespace App;

use App\Database\Connection;
use App\Factory\LoggerFactory;
use Phpmig\Adapter\Illuminate\Database;
use Psr\Log\LoggerInterface;

/**
 * The configuration provider for the App module
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
            'database' => $this->getDatabaseConfig(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'auto' => $this->getInjections(),
            'invokables' => [],
            'aliases' => [
                'db' => Connection::class,
                'phpmig.adapter' => Database::class,
            ],
            'factories' => [
                LoggerInterface::class => LoggerFactory::class,
            ],
        ];
    }

    public function getDatabaseConfig(): array
    {
        return [
            'driver' => '%env(DB_DRIVER)%',
            'charset' => '%env(DB_CHARSET)%',
            'collation' => '%env(DB_COLLATION)%',
            'prefix' => '%env(DB_PREFIX)%',
            'host' => '%env(DB_HOST)%',
            'database' => '%env(DB_NAME)%',
            'port' => '%env(DB_PORT)%',
            'username' => '%env(DB_USER)%',
            'password' => '%env(DB_PASSWORD)%',
        ];
    }

    private function getInjections(): array
    {
        return [
            'types' => [
                Database::class => [
                    'parameters' => [
                        'adapter' => 'db',
                        'tableName' => 'migrations',
                    ]
                ],
                'db' => [
                    'typeOf' => Connection::class,
                ]
            ]
        ];
    }
}
