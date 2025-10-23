<?php
namespace App\Controllers;

use App\Core\Exceptions\DuplicateEntryException;
use App\Core\Exceptions\ValidationException;
use App\Service\UserService;

class UserController {
    // La propiedad ya está definida en el constructor
    // private $userService;
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index(): void{
        header('Content-Type: application/json');
        try {
            $users = $this->userService->getAllUsers();
            http_response_code(200); // OK
            echo json_encode(['data' => $users]);
        } catch (\Exception $e) {
            http_response_code(500); // Internal Server Error
            error_log("Error en UserController::index: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'No se pudieron obtener los usuarios.']);
        }
        exit(); // Se mantiene para la estructura actual
    }

public function create(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Content-Type: application/json');
        http_response_code(405); // Method Not Allowed
        echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
        exit();
    }

        // Lee los datos JSON del cuerpo de la petición
        $json = file_get_contents('php://input');
        $userData = json_decode($json, true);

        // Verifica si la decodificación falló o si no se enviaron datos
        if (json_last_error() !== JSON_ERROR_NONE || empty($userData)) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
            exit();
        }

        header('Content-Type: application/json');

        try {
            $result = $this->userService->registerUser($userData);
            http_response_code(201); // 201 Created
            echo json_encode($result);
        } catch (ValidationException $e) {
            http_response_code(400); // 400 Bad Request
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        } catch (DuplicateEntryException $e) {
            http_response_code(409); // 409 Conflict
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            // Captura cualquier otro error inesperado
            http_response_code(500); // 500 Internal Server Error
            // En un entorno de producción, es mejor no mostrar el mensaje de error detallado
            error_log($e->getMessage()); // Guardar el error para depuración
            echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error inesperado en el servidor.']);
        } finally {
            exit();
        }
}
    
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Opcional: manejar otros métodos si es necesario
            header('Content-Type: application/json');
            http_response_code(405); // 405 Method Not Allowed
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
            exit();
        }
        
        $json = file_get_contents('php://input');
        $credentials = json_decode($json, true);
    
        if (json_last_error() !== JSON_ERROR_NONE || empty($credentials)) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
            exit();
        }
    
        header('Content-Type: application/json');
        try {
            $result = $this->userService->loginUser($credentials);
            http_response_code(200); // 200 OK
            echo json_encode($result);
        } catch (ValidationException $e) {
            http_response_code(401); // 401 Unauthorized es más apropiado para login fallido
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            http_response_code(500); // 500 Internal Server Error
            error_log($e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error inesperado en el servidor.']);
        }
        exit();
    }
}
