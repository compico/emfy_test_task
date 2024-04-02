<?php

namespace App\Helper;

use Psr\Container\ContainerInterface;

class ConfigHelper
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function getWebhookRoute(): string
    {
        return $this->container->get('config')['routes']['hook_url'];
    }
}
