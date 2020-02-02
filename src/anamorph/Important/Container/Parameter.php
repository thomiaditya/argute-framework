<?php

namespace Anamorph\Important\Container;

use Anamorph\Covenant\Container\Container;

class Parameter
{
    /**
     * Hold the container.
     *
     * @var Anamorph\Covenant\Container\Container
     */
    protected $container;

    /**
     * Contains the abstract that used.
     *
     * @var string
     */
    protected $abstract;

    /**
     * Add the container and the abstract properties.
     *
     * @param Anamorph\Covenant\Container\Container $container
     * @param string $abstract
     */
    public function __construct(Container $container, $abstract)
    {
        $this->container = $container;
        $this->abstract = $abstract;
    }

    /**
     * Add params to define class.
     *
     * @param array $parameters
     * 
     * @return void
     */
    public function withParams(array $parameters)
    {
        $this->container->addToParams($this->abstract, $parameters);
    }
}