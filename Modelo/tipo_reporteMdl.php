<?php
class TipoReporteMdl
{
    private mysqli $con;

    public function conectarBD(): mysqli
    {
        $this->con = new mysqli("localhost", "root", "", "shakti");

        if ($this->con->connect_error) {
            die("Error de conexión: " . $this->con->connect_error);
        }
        return $this->con;
    }

    public function verTipos()
    {
        $this->conectarBD();
        $sql = "SELECT id_tipo_reporte, nombre_reporte, tipo_objetivo FROM tipo_reporte";
        $result = $this->con->query($sql);

        if (!$result || $result->num_rows === 0) {
            echo '<tr><td colspan="4" class="text-center"><h4>Sin registros</h4></td></tr>';
            return;
        }

        while ($row = $result->fetch_assoc()) {
            $id     = htmlspecialchars($row['id_tipo_reporte']);
            $nombre = htmlspecialchars($row['nombre_reporte']);
            $tipo   = htmlspecialchars($row['tipo_objetivo']);

            echo '<tr>';
            echo "<td>$id</td>";
            echo "<td>$nombre</td>";
            echo "<td>$tipo</td>";

            echo '<td>
                    <a href=\"editar.php?id=' . $id . '\" class=\"btn btn-sm btn-outline-primary\">Editar</a>
                    <a href=\"eliminar.php?id=' . $id . '\" class=\"btn btn-sm btn-outline-danger\">Eliminar</a>
                  </td>';
            echo '</tr>';
        }
    }

    public function cerrarBD()
    {
        if (isset($this->con)) {
            $this->con->close();
        }
    }

}
