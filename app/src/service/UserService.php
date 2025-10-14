<?php
require_once __DIR__ . '/../repository/UserRepository.php';

class userService {
    private $userRepository;
    private const DEFAULT_ROLE_ID = 1;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $data) {
        /*if(empty($data['nombre']) || empty($data['apellido']) || empty($data['dni']) || empty($data['email']) || empty($data['password'])) {
            return ['status' => 'error', 'message' => 'Todos los campos son obligatorios.'];
        }
*/
        $data['id_rol'] = self::DEFAULT_ROLE_ID;
        if($this->userRepository->addUser($data)){
            return ['status' => 'success', 'message' => 'Usuario registrado exitosamente.'];
        }
        return ['status' => 'error', 'message' => 'Error al registrar el usuario.'];
    }

    public function getUserById($id) {
        return $this->userRepository->getUserById($id);
    }

    public function getAllUsers(){
        return $this->userRepository->getAllusers();
    }
}