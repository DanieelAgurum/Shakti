<?php
date_default_timezone_set('America/Mexico_City');

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

    public function inicializar($tit, $not, $idUsuaria)
    {
        $this->titulo = $tit;
        $this->nota = $not;
        $this->fecha = date('Y-m-d H:i:s'); // o puedes omitirlo si usas DEFAULT en SQL
        $this->idUsuaria = $idUsuaria;
    }

    public function insertarNota()
    {
        $con = $this->conectarBD();
        $sql = "INSERT INTO notas (titulo, nota, fecha, id_usuaria) 
        VALUES ('$this->titulo', '$this->nota', '$this->fecha', '$this->idUsuaria')";
        if (mysqli_query($con, $sql)) {
            header("Location: ../Vista/usuaria/perfil.php?status=success&message=" . urlencode("Nota creada correctamente"));
        } else {
            header("Location: ../Vista/usuaria/perfil.php?status=error&message=" . urlencode("Sesión no iniciada"));
        }
    }
    public function obtenerNotas()
    {
        $con = $this->conectarBD();
        $sql = "SELECT * FROM notas ORDER BY fecha DESC";
        $resultado = mysqli_query($con, $sql);
        $notas = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $notas[] = $fila;
        }
        return $notas;
    }
    public function obtenerNotaPorId($id)
    {
        $con = $this->conectarBD();
        $sql = "SELECT * FROM notas WHERE id = $id";
        $resultado = mysqli_query($con, $sql);
        if ($fila = mysqli_fetch_assoc($resultado)) {
            return $fila;
        } else {
            return null;
        }
    }
    public function actualizarNota($id, $tit, $not)
    {
        $con = $this->conectarBD();
        $sql = "UPDATE notas SET titulo = '$tit', nota = '$not' WHERE id_nota = $id";
        if (mysqli_query($con, $sql)) {
            header("Location: ../Vista/usuaria/perfil.php?status=success&message=" . urlencode("Nota actualizada correctamente"));
        } else {
            header("Location: ../Vista/usuaria/perfil.php?status=error&message=" . urlencode("Sesión no iniciada"));
        }
    }
    public function eliminarNota($id)
    {
        $con = $this->conectarBD();
        $sql = "DELETE FROM notas WHERE id_nota = $id";
        if (mysqli_query($con, $sql)) {
            if (mysqli_query($con, $sql)) {
                header("Location: ../Vista/usuaria/perfil.php?status=success&message=" . urlencode("Nota eliminada correctamente"));
            } else {
                header("Location: ../Vista/usuaria/perfil.php?status=error&message=" . urlencode("Sesión no iniciada"));
            }
        }
    }
}
