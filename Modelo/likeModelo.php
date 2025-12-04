<?php
class likeModelo
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli(
            'localhost',
            'root',
            '',
            'shakti'
        );

        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    public function usuarioYaDioLike($id_usuaria, $id_publicacion)
    {
        $stmt = $this->conn->prepare("SELECT id_like FROM likes_publicaciones WHERE id_usuaria = ? AND id_publicacion = ?");
        $stmt->bind_param("ii", $id_usuaria, $id_publicacion);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function darLike($id_usuaria, $id_publicacion)
    {
        $stmt = $this->conn->prepare("INSERT INTO likes_publicaciones (id_usuaria, id_publicacion) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_usuaria, $id_publicacion);
        $stmt->execute();
    }

    public function quitarLike($id_usuaria, $id_publicacion)
    {
        $stmt = $this->conn->prepare("DELETE FROM likes_publicaciones WHERE id_usuaria = ? AND id_publicacion = ?");
        $stmt->bind_param("ii", $id_usuaria, $id_publicacion);
        $stmt->execute();
    }

    public function contarLikes($id_publicacion)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM likes_publicaciones WHERE id_publicacion = ?");
        $stmt->bind_param("i", $id_publicacion);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        return $total;
    }
}
