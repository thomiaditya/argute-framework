<?php

namespace Anamorph\Event;

use Anamorph\Covenant\Container\Container;
use Anamorph\Covenant\Event\Dispatcher;
use Anamorph\Important\Container\LogicException;

class EventDispatcher implements Dispatcher
{
    /**
     * The container or the application that used.
     *
     * @var \Anamorph\Covenant\Container\Container
     */
    protected $container;

    /**
     * All listened listeners goes here.
     *
     * @var array[]
     */
    protected $listened;

    /**
     * EventDispatcher constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function listen($event, $listener, $priority = false)
    {
        if(is_string($listener)) {
            $listener = $this->parseStringListener($listener);
        }

        $this->listened[$event][] = $this->makeListener($listener);
    }

    /**
     * Parse the string listener to an array.
     *
     * @param string $listener
     *
     * @return array[]
     */
    protected function parseStringListener($listener)
    {
        try {
            $parsed = explode('::', $listener);
        } catch (LogicException $e) {
            throw $e;
        }

        $parsed[0] = $this->addNamespace($parsed[0]);

        return $parsed;
    }

    /**
     * Add namespace if there is no namespace.
     *
     * @param $parsed
     *
     * @return string
     */
    protected function addNamespace($parsed)
    {
        if(class_exists($parsedNamespace = 'Anamorph\Event\Listeners\\' . $parsed)) {
            return $parsedNamespace;
        }
    }

    /**
     * Create listener closure.
     *
     * @param array $listener
     *
     * @return \Closure
     */
    protected function makeListener(array $listener)
    {
        return function (array $params) use ($listener) {
            return call_user_func_array($listener, $params);
        };
    }

    /**
     * @inheritDoc
     */
    public function dispatch($event, $object)
    {
        if(is_string($object)) {
            /**
             * Its injecting the event object.
             * 
             * @var \Anamorph\Covenant\Event\Event
             */
            $object = $this->container->develop($object);
        }

        if($object->isPropagationStopped()) {
            /** @todo Fill the logic if propagation is stopped what should to do. */
        }
        
        /** @todo Replace this call function for better. Its cannot use end method! */
        return call_user_func(end($this->listened[$event]), [$object]);
    }
}