<?php

// 1. Incluir el autoloader de Composer. ¡Esto carga todo lo que necesitas!
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// 2. Usar los namespaces para referenciar las clases
use App\Controllers\UserController;
use App\Service\UserService;
use App\Middleware\AuthMiddleware;
use App\Repository\UserRepository;

// 3. Cargar las rutas (esto se mantiene igual)
$routes = require_once __DIR__ . '/../app/src/Core/routes.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Lógica de enrutamiento mejorada
$routeFound = null;
$params = [];

if (isset($routes[$requestMethod])) {
    foreach ($routes[$requestMethod] as $route => $handler) {
        // Convierte la ruta con placeholders (ej: /users/{id}) en una expresión regular
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[0-9]+)', $route);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestUri, $matches)) {
            $routeFound = $handler;
            // Extrae los parámetros de la URL (ej: el 'id')
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[] = $value;
                }
            }
            break;
        }
    }
}

if ($routeFound) {
    // --- INICIO DE LA LÓGICA DEL MIDDLEWARE ---
    if (isset($routeFound['auth']) && $routeFound['auth'] === true) {
        $authMiddleware = new AuthMiddleware();
        $userDataFromToken = $authMiddleware->handle(); // Si el token es inválido, el script muere aquí.
    }
    // --- FIN DE LA LÓGICA DEL MIDDLEWARE ---
    $controllerName = $routeFound['controller'];
    $methodName = $routeFound['method'];
    
    // Añadimos el namespace completo al nombre del controlador
    $fullControllerName = "App\\Controllers\\" . $controllerName;

    // Instanciamos el controlador y sus dependencias (esto podría mejorarse con un Contenedor de Inyección de Dependencias)
    $userRepository = new UserRepository();
    $userService = new UserService($userRepository);
    $controller = new $fullControllerName($userService);

    // Llamamos al método correcto, pasándole los parámetros de la URL
    call_user_func_array([$controller, $methodName], $params);
} else {
    // Si la ruta no se encuentra
    http_response_code(404);
    echo "404 - Página no encontrada.";
}