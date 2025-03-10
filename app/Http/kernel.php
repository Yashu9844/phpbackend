<?php

protected $middlewareGroups = [
    'api' => [
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authenticate::class, // Use auth middleware
        'throttle:api',
    ],
];

