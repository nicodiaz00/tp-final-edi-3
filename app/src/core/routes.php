<?php

require_once __DIR__ . '/../controllers/UserController.php';

return [
    'GET' => [
        '/users' => ['controller' => 'UserController', 'method' => 'index'],
        '/users/{id}' => ['controller' => 'UserController', 'method' => 'show']
    ],
    'POST' => [
        '/users' => ['controller' => 'UserController', 'method' => 'create']
    ],
];