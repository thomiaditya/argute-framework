<?php

namespace Anamorph\Covenant\Http;

interface Kernel
{
    /**
     * Handle the HTTP request and return the response
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Anamorph\Covenant\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request);
}