<?php

class mostrarMetricasMdl
{
    private $con;

    public function __construct()
    {
        $this->conectarBD();
    }

    private function conectarBD()
    {
        $this->con = mysqli_connect("localhost", "root", "", "shakti")
            or die("Problemas con la conexión a la base de datos");
    }

    public function mostrar()
    {
        $sql = "SELECT vista, SUM(tiempo_estancia) AS tiempo_estancia, COUNT(*) AS vistas 
                FROM metricas 
                GROUP BY vista 
                ORDER BY vista;";
        $consulta = mysqli_query($this->con, $sql);

        $vistas = [];
        $tiempos = [];

        while ($reg = mysqli_fetch_assoc($consulta)) {
            // Arreglo para la gráfica de pastel (vista, número de vistas)
            $vistas[] = [$reg['vista'], (int)$reg['vistas']];
            // Arreglo para la gráfica de barras (vista, tiempo total)
            $tiempos[] = [$reg['vista'], (float)$reg['tiempo_estancia']];
        }

        return [
            'vistas' => $vistas,
            'tiempos' => $tiempos
        ];
    }
}
