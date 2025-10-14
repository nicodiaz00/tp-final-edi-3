<?php

require_once __DIR__ . '/../app/src/controllers/UserController.php';
require_once __DIR__ . '/../app/src/service/UserService.php';
require_once __DIR__ . '/../app/src/repository/UserRepository.php';

$routes = require_once __DIR__ . '/../app/src/core/routes.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// 4. Lógica de enrutamiento
if (isset($routes[$requestMethod][$requestUri])) {
    $routeInfo = $routes[$requestMethod][$requestUri];
    $controllerName = $routeInfo['controller'];
    $methodName = $routeInfo['method'];

    // Instanciamos el controlador y sus dependencias
    $userRepository = new UserRepository();
    $userService = new UserService($userRepository);
    $controller = new $controllerName($userService);
    
    // Llamamos al método correcto
    $controller->$methodName();

} else {
    // Si la ruta no se encuentra
    http_response_code(404);
    echo "404 - Página no encontrada.";
}