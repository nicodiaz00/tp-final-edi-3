<?php
require_once __DIR__ . '/../repository/UserRepository.php';

class userService {
    private $userRepository;
    private $defaultRoleId = 1;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function registerUser(array $data) {
        if(empty($data['nombre']) || empty($data['apellido']) || empty($data['dni']) || empty($data['email']) || empty($data['password']) || empty($data['id_rol'])) {
            return ['status' => 'error', 'message' => 'Todos los campos son obligatorios.'];
        }

        $data['id_rol'] = $defaultRoleId;
        if($this->userRepository->addUser($data)){
            return ['status' => 'success', 'message' => 'Usuario registrado exitosamente.'];
        }
        return ['status' => 'error', 'message' => 'Error al registrar el usuario.'];
    }

    public function getUserById($id) {
        return $this->userRepository->getUserById($id);
    }
}