<?php

declare(strict_types=1);

use Crm\Adapter\ArrayAccessContainerAdapter;
use Laminas\ServiceManager\ServiceManager;


/** @var ServiceManager $serviceManager */
$serviceManager = require __DIR__ . '/container.php';

$container = new ArrayAccessContainerAdapter($serviceManager);

$container['phpmig.migrations_path'] = MIGRATION_DIR;

return $container;