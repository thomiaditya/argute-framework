<?php

namespace Anamorph\Http\Kernel;

use Anamorph\Http\Request\Request;
use Anamorph\Http\Response\Response;
use Anamorph\Important\Application\Application;

class Kernel
{
    /**
     * Apps that used.
     *
     * @var \Anamorph\Covenant\Application
     */
    protected $app;

    /**
     * Http Request instance.
     *
     * @var \Anamorph\Covenant\Http\Request
     */
    protected $request;

    /**
     * Http Response instance.
     *
     * @var \Anamorph\Covenant\Http\Response
     */
    protected $response;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request)
    {
        $this->request = $request;

        $response = new Response(
            'Content',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response->prepare($request);
    }
} 