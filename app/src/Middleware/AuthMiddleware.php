<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class AuthMiddleware {
    private $jwtSecretKey;

    public function __construct() {
        $this->jwtSecretKey = $_ENV['JWT_SECRET_KEY'] ?? null;
        if (!$this->jwtSecretKey) {
            throw new \RuntimeException("La clave secreta JWT no está configurada.");
        }
    }

    public function handle() {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if (!$authHeader) {
            $this->unauthorized('Token no proporcionado.');
        }

        // El token debe venir en el formato "Bearer <token>"
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $this->unauthorized('Formato de token inválido.');
        }

        $token = $matches[1];

        try {
            // Decodificamos el token. La librería verificará la firma y la expiración.
            $decoded = JWT::decode($token, new Key($this->jwtSecretKey, 'HS256'));
            // Opcional: Podrías devolver los datos del usuario si los necesitas en el controlador.
            return (array) $decoded->data;
        } catch (ExpiredException $e) {
            $this->unauthorized('El token ha expirado.');
        } catch (\Exception $e) {
            // Cualquier otro error (firma inválida, token malformado, etc.)
            $this->unauthorized('Token inválido.');
        }
    }

    private function unauthorized(string $message) {
        header('Content-Type: application/json');
        http_response_code(401); // 401 Unauthorized
        echo json_encode(['status' => 'error', 'message' => $message]);
        exit();
    }
}