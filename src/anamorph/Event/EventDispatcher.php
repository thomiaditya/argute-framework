<?php

namespace Anamorph\Event;

use Anamorph\Covenant\Application\Application;
use Anamorph\Covenant\Event\Dispatcher;
use Anamorph\Important\Container\LogicException;
use Exception;

class EventDispatcher implements Dispatcher
{
    /**
     * The container or the application that used.
     *
     * @var \Anamorph\Covenant\Application\Application
     */
    protected $application;

    /**
     * All listened listeners goes here.
     *
     * @var array[]
     */
    protected $listened = [];

    /**
     * If already dispatched, the listener name will save here.
     *
     * @var array[]
     */
    protected $dispatched = [];

    /**
     * Subscribed listener.
     *
     * @var array[]
     */
    protected $subscribed = [];

    /**
     * EventDispatcher constructor.
     *
     * @param \Anamorph\Covenant\Application\Application $container
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @inheritDoc
     */
    public function listen($event, $listener, $priority = 0)
    {
        if(is_string($listener)) {
            $listener = $this->parseStringListener($listener);
        }
        
        // Add to listened property and the event name is an offset. It will added to array
        // first and then it will be sorted automatically, so it sorted by how much priority
        // to listen first. When dispatched, it will call the priority first.
        $this->listened[$event][] = [
            'listener' => $this->makeListener(array(new $listener[0], $listener[1])),
            'priority' => $priority
        ];

        $this->sortListened($event);
    }

    /**
     * Sort the listened property as the priority given.
     *
     * @param string $event
     * 
     * @return void
     */
    protected function sortListened($event)
    {
        usort($this->listened[$event], function ($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });
    }

    /**
     * @inheritDoc
     */
    public function subscribe($subscriber)
    {
        $listeners = $subscriber->subscribed();

        foreach($listeners as $event => $args) {
            if(is_string($args)) {
                $this->listen($event, [$subscriber, $args]);
                continue;
            }

            foreach($args as $arg) {
                if(is_string($arg)) {
                    $this->listen($event, [$subscriber, $arg]);
                    continue;
                }

                $this->listen($event, [$subscriber, $arg[0]], $arg[1]);
            }
        }
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

        if (! class_exists($parsed[0])) {
            throw new LogicException("You are entering on wrong namespace!");
        }

        return $parsed;
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
        return function (...$params) use ($listener) {
            call_user_func_array($listener, $params);
        };
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return isset($this->listened[$id]);
    }

    /**
     * @inheritDoc
     */
    public function dispatch($event, $object)
    {
        if(! $this->has($event)) {
            throw new Exception("Listener doesnt added yet!");
        }

        if(is_string($object)) {
            /**
             * Its injecting the event object.
             * 
             * @var \Anamorph\Covenant\Event\Event
             */
            $object = $this->application->develop($object);
        }

        if ($object->isPropagationStopped()) {
            return;
        }

        foreach($this->listened[$event] as $event) {
            $event['listener']($object);
        }
    }
}