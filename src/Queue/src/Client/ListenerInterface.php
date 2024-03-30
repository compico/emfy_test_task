<?php

declare(strict_types=1);

namespace Queue\Client;

interface ListenerInterface
{
    public const DEFAULT_TUBE = 'default';
    public const CRM_TUBE     = 'crm';
}
