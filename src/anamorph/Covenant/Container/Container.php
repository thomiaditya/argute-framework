<?php

namespace Anamorph\Covenant\Container;

use ArrayAccess;
use Psr\Container\ContainerInterface;

interface Container extends ContainerInterface, ArrayAccess
{
    /**
     * Define an definition to contain in this container.
     * 
     * Its useful for insteface.
     *
     * @param string $abstract
     * @param \Closure|string|null $concrete
     * @param boolean $duplicated
     * 
     * @return Anamorph\Important\Container\Parameter
     */
    public function define($abstract, $concrete = null, $singleton = false);

    /**
     * Add a singleton instance.
     * 
     * Singleton is a design pattern which allow us to define an instance once
     * and use it in every get method.
     *
     * @param string $abstract
     * @param \Closure|string|null $concrete
     * 
     * @return Anamorph\Important\Container\Parameter
     */
    public function singleton($abstract, $concrete = null);

    /**
     * Add an instance to container.
     * 
     * Meaning that an existing instance will save to container as singleton
     * so, it will be not duplicated.
     *
     * @param string $abstract Abstract for identify the instance.
     * @param object $instance
     * 
     * @throws ContainerExceptionInterface
     */
    public function instance($abstract, $instance);

    /**
     * Set the name of an abstract.
     * 
     * If a name set to the abstract, its belong with that abstract. So, call the name
     * thats same as call the abstract.
     *
     * @param string $name
     * @param string $abstract
     * 
     * @return void
     * 
     * @throws Anamorph\Important\Container\LogicException
     */
    public function name($abstract, $name);

    /**
     * Set an definite build to specify an builded object.
     *
     * @param string $abstract
     * 
     * @return Anamorph\Important\Container\DefiniteBuilding
     */
    public function definite($abstract);

    /**
     * Add a definite to its property.
     *
     * @param string $abstract
     * @param string $concrete
     * @param string $implement
     * 
     * @return void
     */
    public function addDefinite($abstract, $concrete, $implement);

    /**
     * Develop an object from abstract given.
     *
     * @param string $object
     * 
     * @return object Return object from the abstract.
     */
    public function develop($object);

    /**
     * Define a path to container.
     *
     * @param string $path
     * @param string $directory
     * 
     * @return void
     */
    public function path($path, $directory);

    /**
     * Getting the path given.
     *
     * @param string $path
     * 
     * @return string
     * 
     * @throws Exception
     */
    public function getPath($path);
}