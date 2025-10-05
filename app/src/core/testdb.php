<?php

require_once __DIR__ . '/database.php';

$db = Database::getInstance()->getConnection();

try {
    // Hacemos una consulta simple para probar
    $stmt = $db->query("SELECT * FROM Rol");
    $roles = $stmt->fetchAll();

    echo "Roles en la base de datos:\n";
    foreach ($roles as $rol) {
        echo "- " . $rol['nombre_rol'] . "\n";
    }
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}