<?php

date_default_timezone_set('America/Mexico_City');
define('CLAVE_SECRETA', 'xN7$wA9!tP3@zLq6VbE2#mF8jR1&yC5Q');

class Notas
{
    private $urlBase;
    private $titulo;
    private $nota;
    private $fecha;
    private $idUsuaria;

    public function conectarBD()
    {
        $con = mysqli_connect("localhost", "root", "", "shakti") or die("Problemas con la conexión a la base de datos");
        return $con;
    }

    private function cifrarAES($texto)
    {
        $ci = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $cifrado = openssl_encrypt($texto, 'aes-256-cbc', CLAVE_SECRETA, 0, $ci);
        return base64_encode($ci . $cifrado);
    }

    private function descifrarAES($textoCodificado)
    {
        $datos = base64_decode($textoCodificado);
        $ci_length = openssl_cipher_iv_length('aes-256-cbc');
        $ci = substr($datos, 0, $ci_length);
        $cifrado = substr($datos, $ci_length);
        return openssl_decrypt($cifrado, 'aes-256-cbc', CLAVE_SECRETA, 0, $ci);
    }

    public function inicializar($tit, $not, $idUsuaria)
    {
        $this->titulo = $this->cifrarAES($tit);
        $this->nota = $this->cifrarAES($not);
        $this->fecha = date('Y-m-d H:i:s');
        $this->idUsuaria = $idUsuaria;
    }

    public function insertarNota()
    {
        $con = $this->conectarBD();
        $sql = "INSERT INTO notas (titulo, nota, fecha, id_usuaria) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssi", $this->titulo, $this->nota, $this->fecha, $this->idUsuaria);

        if ($stmt->execute()) {
            header("Location: ../Vista/usuaria/perfil.php?status=success&message=" . urlencode("Nota creada correctamente"));
        } else {
            header("Location: ../Vista/usuaria/perfil.php?status=error&message=" . urlencode("Error al guardar la nota"));
        }

        $stmt->close();
        $con->close();
    }

    public function obtenerNotas($id_usuaria)
    {
        $con = $this->conectarBD();
        $sql = "SELECT * FROM notas WHERE id_usuaria = ? ORDER BY fecha DESC";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id_usuaria);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $notas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $fila['titulo'] = $this->descifrarAES($fila['titulo']);
            $fila['nota'] = $this->descifrarAES($fila['nota']);
            $notas[] = $fila;
        }

        $stmt->close();
        $con->close();

        return $notas;
    }

    public function obtenerNotaPorId($id)
    {
        $con = $this->conectarBD();
        $sql = "SELECT * FROM notas WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();

        if ($fila) {
            $fila['titulo'] = $this->descifrarAES($fila['titulo']);
            $fila['nota'] = $this->descifrarAES($fila['nota']);
            return $fila;
        }
        return null;
    }

    public function actualizarNota($id, $tit, $not)
    {
        $con = $this->conectarBD();
        $tit = $this->cifrarAES($tit);
        $not = $this->cifrarAES($not);

        $sql = "UPDATE notas SET titulo = ?, nota = ? WHERE id_nota = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $tit, $not, $id);

        if ($stmt->execute()) {
            header("Location: ../Vista/usuaria/perfil.php?status=success&message=" . urlencode("Nota actualizada correctamente"));
        } else {
            header("Location: ../Vista/usuaria/perfil.php?status=error&message=" . urlencode("Error al actualizar"));
        }

        $stmt->close();
        $con->close();
    }

    public function eliminarNota($id)
    {
        $con = $this->conectarBD();
        $sql = "DELETE FROM notas WHERE id_nota = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: ../Vista/usuaria/perfil.php?status=success&message=" . urlencode("Nota eliminada correctamente"));
        } else {
            header("Location: ../Vista/usuaria/perfil.php?status=error&message=" . urlencode("Error al eliminar"));
        }

        $stmt->close();
        $con->close();
    }
}
