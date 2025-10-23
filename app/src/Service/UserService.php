<?php
namespace App\Service;

use App\Core\Exceptions\ValidationException;
use App\Repository\UserRepository;
use Firebase\JWT\JWT;

class UserService {
    private $userRepository;
    private const DEFAULT_ROLE_ID = 1;
    private $jwtSecretKey;
    private const JWT_ALGORITHM = 'HS256';

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
        // Usamos $_ENV que es más fiable con la librería Dotenv.
        $secret = $_ENV['JWT_SECRET_KEY'] ?? null;

        if (!$secret) {
            throw new \RuntimeException("La clave secreta JWT no está configurada en el archivo .env");
        }
        $this->jwtSecretKey = $secret;
    }

    public function registerUser(array $data) {
        // 1. Validación de datos (lógica de negocio)
        $requiredFields = ['nombre', 'apellido', 'dni', 'email', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                // Es mejor lanzar una excepción para un manejo de errores más claro
                throw new ValidationException("El campo '{$field}' es obligatorio.");
            }
        }

        // 2. Aplicar reglas de negocio (ej: rol por defecto)
        $data['id_rol'] = self::DEFAULT_ROLE_ID;

        // 3. Llamar al repositorio para la persistencia
        if ($this->userRepository->addUser($data)) {
            return ['status' => 'ok', 'message' => 'Usuario registrado exitosamente.'];
        }
        return ['status' => 'error', 'message' => 'Error al registrar el usuario.'];
    }

    public function loginUser(array $data) {
        // 1. Validación de campos
        if (empty($data['email']) || empty($data['password'])) {
            throw new ValidationException("Email y password son obligatorios.");
        }

        // 2. Buscar usuario por email
        $user = $this->userRepository->getUserByEmail($data['email']);

        if (!$user) {
            throw new ValidationException("Credenciales inválidas."); // Mensaje genérico por seguridad
        }

        // 3. Verificar la contraseña
        if (!password_verify($data['password'], $user['password'])) {
            throw new ValidationException("Credenciales inválidas."); // Mensaje genérico por seguridad
        }

        // 4. Generar el token JWT si la contraseña es correcta
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // Token válido por 1 hora (3600 segundos)
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'iss' => 'http://localhost:8080', // Emisor del token (tu dominio)
            'data' => [
                'id' => $user['id_usuario'],
                'email' => $user['email'],
                'rol' => $user['id_rol']
            ]
        ];

        $token = JWT::encode($payload, $this->jwtSecretKey, self::JWT_ALGORITHM);

        return ['status' => 'ok', 'token' => $token];
    }

    public function getUserById($id) {
        return $this->userRepository->getUserById($id);
    }

    public function getAllUsers(){
        return $this->userRepository->getAllusers();
    }
}