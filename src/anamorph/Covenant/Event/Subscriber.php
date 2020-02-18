<?php

namespace Anamorph\Covenant\Event;

interface Subscriber
{
    /**
     * Add some listener to event dispatcher class.
     * 
     * Use 'listen' method from dispatcher parameter to listen. Also you can use other
     * function from event dispatcher dependency.
     *
     * @param \Anamorph\Covenant\Event\Dispatcher $dispatcher
     * 
     * @return array Just return an array contain the information that should be lsitened.
     * This are the rule:
     * [$eventname => $methodname] |
     * [$eventname => [[$methodname, $priority]]] |
     * [$eventname => [$methodname, [$methodname, $priority]]]
     */
    public function subscribed();
} 