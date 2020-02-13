<?php

namespace Anamorph\Covenant\Event;

interface Dispatcher
{
    /**
     * Add a listener object to dispatcher.
     *
     * @param string $event Event name that can call in the future.
     * @param \Closure|array|null|string $listener Listener that will be linked to event.
     * @param bool $priority Set if it listener is a priority.
     *
     * @return mixed
     */
    public function listen($event, $listener, $priority = false);

    /**
     * Dispatch an event.
     *
     * @param string $event The event that will be dispatched.
     * @param \Anamorph\Covenant\Event\Event|string $object The object.
     *
     * @return mixed
     */
    public function dispatch($event, $object);
}