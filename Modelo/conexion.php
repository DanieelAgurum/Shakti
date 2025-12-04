<?php
class ConectarDB
{
    private $server = "mysql:host=localhost;dbname=shakti;charset=utf8mb4";
    private $user = "root";
    private $pass = "";
    private $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    protected $conn;

    public function open()
    {
        try {
            $this->conn = new PDO($this->server, $this->user, $this->pass, $this->options);
            $this->conn->exec("SET time_zone = '-06:00'");

            return $this->conn;
        } catch (PDOException $e) {
            echo "❌ Error de conexión: " . $e->getMessage();
            return null;
        }
    }

    public function close()
    {
        $this->conn = null;
    }
}
