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
     * @var array[]
     */
    protected $loadedServices = [];

    /**
     * The path to home.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Name that set first when application running.
     *
     * @var array[]
     */
    protected $primaryNames = [
        'application' => [Application::class, ApplicationCovenant::class]
    ];

    /**
     * Set the home path.
     *
     * @param string $basePath
     * @return Application
     */
    public function run($basePath)
    {
        $this->basePath = $basePath;
        $this->initErrorHandlingWhoops();

        $this->setPrimaryDefining();
        $this->setPrimaryName();

        return $this;
    }

    /**
     * Set the first defining.
     *
     * @return void
     */
    protected function setPrimaryDefining()
    {
        $this->singletonIf('application', $this);
    }

    /**
     * Create singleton defining.
     *
     * @param string $abstract
     * @param string|object|null $concrete
     * 
     * @return void
     */
    public function singletonIf($abstract, $concrete)
    {
        if(is_object($concrete)) {
            $this->instance($abstract, $concrete);
            return;
        }

        $this->singleton($abstract, $concrete);
    }

    /**
     * Set the first naming when application run.
     *
     * @return void
     */
    protected function setPrimaryName()
    {
        foreach($this->primaryNames as $name => $abstracts) {
            foreach($abstracts as $abstract) {
                $this->name($abstract, $name);
            }
        }
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