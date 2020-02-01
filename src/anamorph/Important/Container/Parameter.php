<?php

namespace Anamorph\Important\Container;

use Anamorph\Covenant\Container\Container;

class Parameter
{
    /**
     * Hold the container.
     *
     * @var [type]
     */
    protected $container;

    protected $abstract;

    public function __construct(Container $container, $abstract)
    {
        $this->container = $container;
        $this->abstract = $abstract;
    }

    public function withParams(array $parameters)
    {
        $this->container->addToParams($this->abstract, $parameters);
    }
}