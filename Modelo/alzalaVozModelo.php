<?php
class AlzaLaVozModelo {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function guardarResultado($id_usuaria, $resultado_test, $tipo_violencia) {
        $sql = "INSERT INTO alza_la_voz (id_usuaria, resultado_test, tipo_violencia, fecha_realizacion) 
                VALUES (:id_usuaria, :resultado_test, :tipo_violencia, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_usuaria' => $id_usuaria,
            ':resultado_test' => $resultado_test,
            ':tipo_violencia' => $tipo_violencia
        ]);
    }

    // Obtener la fecha de la última prueba realizada
    public function obtenerUltimaPrueba($id_usuaria) {
        $sql = "SELECT fecha_realizacion FROM alza_la_voz WHERE id_usuaria = :id_usuaria ORDER BY fecha_realizacion DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_usuaria' => $id_usuaria]);
        $resultado = $stmt->fetch();
        return $resultado ? $resultado['fecha_realizacion'] : null;
    }
}
