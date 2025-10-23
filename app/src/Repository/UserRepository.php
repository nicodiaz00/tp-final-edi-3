<?php
namespace App\Repository;

use App\Core\Exceptions\DuplicateEntryException;
use App\Core\Database;
use PDO;
use PDOException;

class UserRepository {

    private $db;
    
    public function __construct() {
        try{
            $this->db = Database::getInstance()->getConnection();
        } catch (Exception $e) {
            error_log("Error al obtener la conexión: " . $e->getMessage());
        }
  
    }
    public function addUser(array $data) {
    try {
        error_log(print_r($data, true));
        $sql = "INSERT INTO Usuario (nombre, apellido, dni, email, password, id_rol)
                VALUES (:nombre, :apellido, :dni, :email, :password, :id_rol)";
        
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':nombre' => $data['nombre'],
            ':apellido' => $data['apellido'],
            ':dni' => $data['dni'],
            ':email' => $data['email'],
            ':password' => $data['password'], // Ya viene hasheada desde el servicio
            ':id_rol' => $data['id_rol']
        ]);

        return true;

    } catch (PDOException $e) {
        // El código de error '23000' es estándar para violaciones de integridad (como UNIQUE)
        if ($e->getCode() == '23000') {
            throw new DuplicateEntryException("El email o DNI ya se encuentra registrado.");
        }
        error_log("Error al agregar usuario: " . $e->getMessage());
        return false;
        }
    }

    public function getUserByEmail(string $email) {
        try {
            // Incluimos la contraseña porque la necesitamos para la verificación
            $sql = "SELECT id_usuario, nombre, apellido, dni, email, password, id_rol FROM Usuario WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Error al obtener usuario por email: " . $e->getMessage());
            return null;
        }
    }


        public function getUserById($id) {
        try {
            $sql = "SELECT id_usuario, nombre, apellido, dni, email, id_rol FROM Usuario WHERE id_usuario = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return $data ?: null;

        } catch (PDOException $e) {
            error_log("Error al obtener usuario: " . $e->getMessage());
            return null;
        }
    }

    
    public function getAllUsers() {
        try {
            $sql = "SELECT id_usuario, nombre, apellido, dni, email, id_rol FROM Usuario";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }

    
    public function updateUser($id, array $data) {
        // Si no hay datos para actualizar, no hacemos nada.
        if (empty($data)) {
            return true;
        }

        try {
            // 1. Construir la consulta dinámicamente para soportar actualizaciones parciales
            $fields = [];
            foreach (array_keys($data) as $field) {
                $fields[] = "$field = :$field";
            }
            $sql = "UPDATE Usuario SET " . implode(', ', $fields) . " WHERE id_usuario = :id";

            $stmt = $this->db->prepare($sql);

            // 2. Añadir el id al array de datos para el binding
            $data['id'] = $id;

            // 3. Devolver un booleano simple para indicar éxito/fracaso
            return $stmt->execute($data);
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                throw new DuplicateEntryException("El email o DNI ya se encuentra registrado.");
            }
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    
    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM Usuario WHERE id_usuario=:id";
            $stmt = $this->db->prepare($sql);
            // execute() devuelve true en caso de éxito. Podemos comprobar también las filas afectadas.
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }


}