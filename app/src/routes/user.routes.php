<?php

return [
    'GET' => [
        '/users' => ['controller' => 'UserController', 'method' => 'index', 'auth' => true],
        '/users/{id}' => ['controller' => 'UserController', 'method' => 'show', 'auth' => true]
    ],
    'POST' => [
        '/users' => ['controller' => 'UserController', 'method' => 'create'], // El registro es público
        '/login' => ['controller' => 'UserController', 'method' => 'login'],   // El login es público
    ],
];