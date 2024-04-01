<?php

declare(strict_types=1);

use Laminas\Config\Processor\Token;
use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/const.php';

// Load configuration
$config = require __DIR__ . '/config.php';

$dotenv = new Dotenv();
$dotenv->usePutenv()->load(__DIR__ . '/../.env');

/** @var array $dependencies */
$dependencies = $config['dependencies'];
$dependencies['services']['config'] = $config;

$processor = new Token(getenv(), '%env(', ')%');

$preparedConfigs = (array) $processor->processValue($dependencies);

return new ServiceManager($preparedConfigs);
