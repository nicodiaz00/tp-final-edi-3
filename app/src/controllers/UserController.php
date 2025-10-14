<?php
require_once __DIR__ . '/../service/UserService.php';

class UserController {
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index(): void{
        $users = $this->userService->getAllUsers();
        header('Content-Type: application/json');
        echo json_encode(['data' => $users]);
        exit();
    }
    /*
    public function create():void{
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $userData = $_POST;
            $result = $this->userService->registerUser($userData);

            if($result['status'] === 'ok'){
                http_response_code(201);
            }else{
                http_response_code(400);
            }
            echo json_encode($result);
            exit();
        }
    }
*/

public function create(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Lee los datos JSON del cuerpo de la petición
        $json = file_get_contents('php://input');
        $userData = json_decode($json, true);

        // Verifica si la decodificación falló o si no se enviaron datos
        if (json_last_error() !== JSON_ERROR_NONE || empty($userData)) {
            header('Content-Type: application/json');
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
            exit();
        }

        // Pasa los datos decodificados a tu servicio
        $result = $this->userService->registerUser($userData);

        header('Content-Type: application/json');
        http_response_code($result['status'] === 'ok' ? 201 : 400);
        echo json_encode($result);
        exit();
    }
}
}