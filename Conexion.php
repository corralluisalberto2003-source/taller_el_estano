<?php
// Conexion.php - Clase de conexión Singleton usando PDO
class Conexion {
    private static $instance = null;
    private $pdo;

    private $host = '127.0.0.1';
    private $db   = 'taller_el_estano';
    private $user = 'root';
    private $pass = ''; 
    private $charset = 'utf8mb4';

    private function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException("Error al conectar a la Base de Datos: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Conexion();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>