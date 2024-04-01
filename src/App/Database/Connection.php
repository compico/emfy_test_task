<?php

namespace App\Database;

use Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerInterface;

class Connection extends Manager
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $config = $container->get('config')['database'];

        $this->addConnection($config);
        $this->setAsGlobal();
        $this->bootEloquent();
    }
}
