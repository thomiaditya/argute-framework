<?php


namespace Anamorph\Covenant\Event;

use Psr\EventDispatcher\StoppableEventInterface;

abstract class Event implements StoppableEventInterface
{
    /**
     * Is the Event stopped.
     *
     * @var bool True if it stopped.
     */
    protected $stoppedPropagation = false;

    /**
     * Determine whether the propagation is stopped.
     *
     * @return bool Return false if didnt.
     */
    public function isPropagationStopped(): bool
    {
        return $this->stoppedPropagation;
    }

    /**
     * Set the propagation to stopped.
     *
     * @return void
     */
    public function stopPropagation()
    {
        $this->stoppedPropagation = true;
    }
}