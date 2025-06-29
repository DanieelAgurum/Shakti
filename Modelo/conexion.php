<?php
class ConectarDB
{
    private $server = "mysql:host=localhost;dbname=shakti";
    private $user = "root";
    private $pass = "";
    private $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );
    protected $conn;

    public function open()
    {
        try {
            $this->conn = new PDO($this->server, $this->user, $this->pass, $this->options);
           
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
