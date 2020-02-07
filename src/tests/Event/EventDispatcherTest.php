<?php

use Anamorph\Covenant\Container\Container as ContainerAlias;
use Anamorph\Covenant\Event\Listeners\TestListener;
use Anamorph\Event\EventDispatcher;

require_once 'vendor/autoload.php';

class EventDispatcherTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    function listenTheListener()
    {
        $container = new Anamorph\Important\Container\Container;
        
        $container->instance(ContainerAlias::class, $container);

        $dispatcher = $container->develop(EventDispatcher::class);

        $dispatcher->listen('test.index', [new TestListener, 'index']);
        dd($dispatcher->dispatch('test.index'));
    }
}