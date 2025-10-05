<?php

class Database {
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct(){
        try {
            $this->connection = new PDO('sqlite:' . __DIR__ . '/../../../database/cafeteria_db.sqlite3');
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

             $this->log("✅ Conexión a la base de datos establecida correctamente.\n");
        }catch (PDOException $e) {
            $this->log("Error en la conexión: " . $e->getMessage());
            die("❌ Falló la conexión a la base de datos.");
        }
    }
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    public function getConnection(): PDO {
        return $this->connection;
    }
     private function log(string $message): void {
        $file = __DIR__ . '/../../../logs/db.log';
        $date = date('Y-m-d H:i:s');
        file_put_contents($file, "[$date] $message\n", FILE_APPEND);
    }
}