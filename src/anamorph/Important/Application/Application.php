<?php

namespace Anamorph\Important\Application;

use Anamorph\Covenant\Application\Application as ApplicationCovenant;
use Anamorph\Important\Container\Container;
use \Whoops\Run as WhoopsRun;
use \Whoops\Handler\PrettyPageHandler as PrettyPage;

class Application extends Container implements ApplicationCovenant
{
    /**
     * Loaded services.
     *
     * @var array
     */
    protected $loadedServices = [];

    /**
     * The path to home.
     *
     * @var string
     */
    protected $baseDir;

    /**
     * Set the home path.
     *
     * @param string $baseDir
     */
    public function run($baseDir)
    {
        $this->baseDir = $baseDir;
        $this->initErrorHandlingWhoops();

        return $this;
    }

    /**
     * Initiating Handing error with Whoops.
     *
     * @return void
     */
    public function initErrorHandlingWhoops()
    {
        $whoops = new WhoopsRun;
        $whoops->pushHandler(new PrettyPage);
        $whoops->register();
    }
}