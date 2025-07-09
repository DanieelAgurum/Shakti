<?php
class reportesMdl
{
    private $nickname;
    private $publicacion;
    private $tipo;
    private $id_reporto;
    public $con;

    public function conectarBD()
    {
        $this->con = new mysqli("localhost", "root", "", "shakti");

        if ($this->con->connect_error) {
            echo json_encode([
                'success' => false,
                'message' => 'Error en la conexión a la base de datos: ' . $this->con->connect_error
            ]);
            exit;
        }
    }

    public function inicializar($nickname, $publicacion, $tipo, $id_reporto)
    {
        $this->nickname = $nickname;
        $this->publicacion = $publicacion;
        $this->tipo = $tipo;
        $this->id_reporto = $id_reporto;
    }

    public function agregarReporte()
    {
        // Conectar a la base de datos
        $this->conectarBD();

        // 1. Validar que todos los campos estén presentes
        if (empty($this->nickname) || empty($this->publicacion) || empty($this->tipo) || empty($this->id_reporto)) {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Seleccione una opción válida.'
            ]);
        }

        // 2. Verificar que la publicación exista y obtener la usuaria dueña
        $sqlAutor = "SELECT id_usuarias FROM publicacion WHERE id_publicacion = ?";
        $stmtAutor = $this->con->prepare($sqlAutor);
        $stmtAutor->bind_param("i", $this->publicacion);
        $stmtAutor->execute();
        $resultado = $stmtAutor->get_result();

        if ($resultado->num_rows === 0) {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'La publicación no existe.'
            ]);
        }

        $row = $resultado->fetch_assoc();
        $id_reportada = $row['id_usuarias'];

        // 3. Evitar autoreporte
        if ($id_reportada == $this->id_reporto) {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'No puedes reportar tu propia publicación.'
            ]);
        }

        // 4. Validar que no exista reporte duplicado
        $sqlVerificar = "SELECT id_reporte FROM reportar WHERE id_usuaria = ? AND id_publicacion = ?";
        $stmtVerificar = $this->con->prepare($sqlVerificar);
        $stmtVerificar->bind_param("ii", $this->id_reporto, $this->publicacion);
        $stmtVerificar->execute();
        $verificacion = $stmtVerificar->get_result();

        if ($verificacion->num_rows > 0) {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Ya reportaste este contenido.'
            ]);
        }

        // 5. Insertar nuevo reporte
        $sqlInsertar = "INSERT INTO reportar (id_tipo_reporte, id_usuaria, id_reportada, id_publicacion, fecha) 
                        VALUES (?, ?, ?, ?, NOW())";
        $stmtInsertar = $this->con->prepare($sqlInsertar);
        $stmtInsertar->bind_param("iiii", $this->tipo, $this->id_reporto, $id_reportada, $this->publicacion);

        if ($stmtInsertar->execute()) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Reporte enviado correctamente.'
            ]);
        } else {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Error al enviar el reporte.'
            ]);
        }
    }

    public function verReportes()
    {
        // Implementar si deseas mostrar reportes en el futuro
    }
}