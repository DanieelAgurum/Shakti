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

    public function obtenerTopLikes()
    {
        $sql = "SELECT p.id_publicacion, p.titulo, u.nickname, COUNT(l.id_like) AS total_likes
            FROM publicacion p
            LEFT JOIN likes_publicaciones l ON p.id_publicacion = l.id_publicacion
            LEFT JOIN usuarias u ON p.id_usuarias = u.id
            GROUP BY p.id_publicacion, p.titulo, u.nickname
            ORDER BY total_likes DESC
            LIMIT 5";

        $result = mysqli_query($this->con, $sql);

        $topLikes = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Concatenar título y autor para la etiqueta
                $label = '"' . $row['titulo'] . '" (' . $row['nickname'] . ')';
                $topLikes[] = [$label, (int)$row['total_likes']];
            }
        }
        return $topLikes;
    }
}
