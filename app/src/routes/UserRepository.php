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

        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt->execute([
            ':nombre' => $data['nombre'],
            ':apellido' => $data['apellido'],
            ':dni' => $data['dni'],
            ':email' => $data['email'],
            ':password' => $hashedPassword,
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
        try {
            $sql = "UPDATE Usuario SET nombre=:nombre, apellido=:apellido, dni=:dni, email=:email, id_rol=:id_rol";

            
            if (!empty($data['password'])) {
                $sql .= ", password=:password";
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $sql .= " WHERE id_usuario=:id";
            $stmt = $this->db->prepare($sql);

            
            $data['id'] = $id;

            $stmt->execute($data);
            return ['status' => 'ok', 'message' => 'Usuario actualizado'];

        } catch (PDOException $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'No se pudo actualizar el usuario'];
        }
    }

    
    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM Usuario WHERE id_usuario=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['status' => 'ok', 'message' => 'Usuario eliminado'];

        } catch (PDOException $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'No se pudo eliminar el usuario'];
        }
    }


}
