<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';

class contenidoMdl
{
    private $titulo;
    private $descripcion;
    private $imagen;
    private $url;
    private $fecha;
    private $estatus;
    private $conexion;
    private $urlBase;

    public function conectarBD()
    {
        $con = mysqli_connect("localhost", "root", "", "shakti") or die("Problemas con la conexión a la base de datos");
        return $con;
    }

    public function __construct()
    {
        $this->urlBase = getBaseUrl();
    }

    public function inicializar($titulo, $descripcion, $url, $imagen)
    {
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->url = $url; 
        $this->imagen = $imagen;
    }

    public function agregarContenido()
    {
        $conexion = $this->conectarBD();

        $tituloEscapado = mysqli_real_escape_string($conexion, $this->titulo);
        $verificar = mysqli_query($conexion, "SELECT * FROM contenido WHERE titulo = '$tituloEscapado'")
            or die(mysqli_error($conexion));

        if ($reg = mysqli_fetch_array($verificar)) {
            echo "<script>alert('El contenido con ese título ya existe.'); window.location.href='../Vista/contenido.php';</script>";
            return;
        }

        
        $sql = "INSERT INTO contenido (titulo, descripcion, url, imagen, fecha_publicacion, estatus)
                VALUES (?, ?, ?, ?, NOW(), 1)";
        $stmt = mysqli_prepare($conexion, $sql);

        
        $imagenBinario = !empty($_FILES['imagen']['tmp_name']) ? file_get_contents($_FILES['imagen']['tmp_name']) : null;

        
        mysqli_stmt_bind_param($stmt, "ssss",
            $this->titulo,
            $this->descripcion,
            $this->url,
            $imagenBinario
        );

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Contenido insertado correctamente'); window.location.href='../Vista/admin/contenido.php';</script>";
        } else {
            echo "Error al insertar: " . mysqli_error($conexion);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }

    public function eliminarContenido($id){
        $this->conectarBD();

        $eliminar = "DELETE FROM contenido WHERE id = :id";
        $delete = $this->conexion->prepare($eliminar);
        $delete->bindParam(':id', $id, PDO::PARAM_INT);

        if ($delete->execute()) {
            header("Location: " . $this->urlBase . "/Vista/admin/contenido.php?estado=eliminado");
            exit;
        } else {
            header("Location: " . $this->urlBase . "/Vista/admin/contenido.php?estado=error");
            exit;
        }
    }
}

?>



