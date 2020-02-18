<?php

namespace Anamorph\Covenant\Event;

interface Dispatcher
{
    /**
     * Add a listener object to dispatcher.
     *
     * @param string $event Event name that can call in the future.
     * @param \Closure|array|null|string $listener Listener that will be linked to event.
     * @param int $priority Higher number given will be priority.
     *
     * @return mixed
     */
    public function listen($event, $listener, $priority = 0);

    /**
     * Dispatch an event.
     *
     * @param string $event The event that will be dispatched.
     * @param \Anamorph\Covenant\Event\Event|string $object The object.
     *
     * @return mixed
     */
    public function dispatch($event, $object);

    /**
     * Check whether the dispatcher has the event name.
     *
     * @param string $id
     * 
     * @return boolean
     */
    public function has($id);

    /**
     * Add a subscriber class to event dispatcher.
     * 
     * Subscriber is plenty of listener in one place.
     *
     * @param \Anamorph\Covenant\Event\Subscriber $subscriber
     * 
     * @return void
     */
    public function subscribe($subscriber);
}