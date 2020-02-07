<?php

namespace Anamorph\Covenant\Http;

interface Kernel
{
    /**
     * Handle the HTTP request and return the response
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request);
}