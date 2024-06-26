<?php

declare(strict_types=1);

namespace Crm;

use Crm\ValueObject\OAuthConfig;

/**
 * The configuration provider for the Crm module
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
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [],
            'factories' => [],
            'auto' => [
                'types' => [
                    OAuthConfig::class => [
                        'parameters' => [
                            'integrationId' => '%env(AMO_CLIENT_ID)%',
                            'secretKey' => '%env(AMO_CLIENT_SECRET)%',
                            'redirectDomain' => 'https://%env(APP_DOMAIN)%/api/v1/crm/auth',
                        ],
                    ],
                ],
            ],
        ];
    }
}
