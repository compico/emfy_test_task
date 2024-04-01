<?php

namespace Crm\Adapter;

use ArrayAccess;
use Laminas\ServiceManager\ServiceManager;
use Psr\Container\ContainerInterface;

class ArrayAccessContainerAdapter implements ArrayAccess, ContainerInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * ArrayAccessAdapter constructor.
     *
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * Finds an entry of the container by its identifier and returns it
     * @param string $id
     *
     * @return mixed|object
     */
    public function get($id)
    {
        return $this->serviceManager->get($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     * @param string $id
     *
     * @return bool
     */
    public function has($id): bool
    {
        return $this->serviceManager->has($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->serviceManager->has($offset);
    }

    /**
     * Finds an entry of the container by its identifier and returns it
     *
     * @param mixed $offset
     *
     * @return mixed|object
     */
    public function offsetGet($offset)
    {
        return $this->serviceManager->get($offset);
    }

    /**
     * Set Service
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->serviceManager->setService($offset, $value);
    }

    /**
     * Unsupported
     *
     * @param mixed $offset
     * @deprecated
     */
    public function offsetUnset($offset): void
    {
        // noop
    }
}
