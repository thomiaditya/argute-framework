<?php

namespace Anamorph\Support\ServicesAssistance;

use Anamorph\Covenant\Application\Application;

abstract class ServicesAssistance
{
    /**
     * Application that running.
     *
     * @var Anamorph\Covenant\Application\Application
     */
    protected $app;

    /**
     * Add application instance to service.
     *
     * @param Anamorph\Covenant\Application\Application $app
     */
    public function __construct(Application $app)
    {
       $this->app = $app;
    }

    /**
     * All defining goes here.
     *
     * @return void
     */
    abstract public function defining();
}