<?php

require __DIR__ . '/../vendor/autoload.php';

$app = include_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->develop(\Anamorph\Http\Kernel\Kernel::class);

$response = $kernel->handle(
    \Anamorph\Http\Request\Request::recap()
);

$response->send();