<?php

namespace Anamorph\Session;

use Anamorph\Covenant\Session\Session as SessionInterface;
use SessionHandlerInterface;

class Session implements SessionInterface
{
    /**
     * Session Handler instance.
     *
     * @var \SessionHandlerInterface
     */
    protected $handler;
} 