<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/obtenerLink/obtenerLink.php';

class contenidoMdl
{
    private $id;
    private $titulo;
    private $descripcion;
    private $imagen;
    private $url;
    private $fecha;
    private $estatus;
    private $urlBase;

    // Conexión a la base de datos con mysqli.
    public function conectarBD()
    {
        $con = mysqli_connect("localhost", "root", "", "shakti") or die("Problemas con la conexión a la base de datos");
        return $con;
    }

    public function __construct()
    {
        $this->urlBase = getBaseUrl();
    }

    // He añadido el parámetro $id para que esta función pueda ser utilizada para editar.
    public function inicializar($titulo, $descripcion, $url, $imagen, $id = null)
    {
        $this->id = $id;
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

        if (mysqli_fetch_array($verificar)) {
            echo "<script>alert('El contenido con ese título ya existe.'); window.location.href='../Vista/admin/contenido.php';</script>";
            mysqli_close($conexion);
            return;
        }

        // Se utiliza una sentencia preparada para mayor seguridad.
        $sql = "INSERT INTO contenido (titulo, descripcion, url, imagen, fecha_publicacion, estatus)
                VALUES (?, ?, ?, ?, NOW(), 1)";
        $stmt = mysqli_prepare($conexion, $sql);

        // La imagen ya viene desde el controlador a través del método inicializar().
        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $this->titulo,
            $this->descripcion,
            $this->url,
            $this->imagen
        );

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Contenido insertado correctamente'); window.location.href='../Vista/admin/contenido.php';</script>";
        } else {
            echo "Error al insertar: " . mysqli_error($conexion);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
    
    // Nuevo método para actualizar el contenido existente.
    public function actualizarContenido()
    {
        $conexion = $this->conectarBD();
        
        if ($this->imagen === null) {
            // Si no se proporciona una nueva imagen, se actualizan solo los otros campos.
            $sql = "UPDATE contenido SET titulo = ?, descripcion = ?, url = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param(
                $stmt,
                "sssi",
                $this->titulo,
                $this->descripcion,
                $this->url,
                $this->id
            );
        } else {
            // Si se proporciona una nueva imagen, se actualizan todos los campos, incluida la imagen.
            $sql = "UPDATE contenido SET titulo = ?, descripcion = ?, url = ?, imagen = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param(
                $stmt,
                "ssssi",
                $this->titulo,
                $this->descripcion,
                $this->url,
                $this->imagen,
                $this->id
            );
        }

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Contenido actualizado correctamente'); window.location.href='../Vista/admin/contenido.php';</script>";
        } else {
            echo "Error al actualizar: " . mysqli_error($conexion);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }


    // Método corregido para eliminar el contenido.
    public function eliminarContenido($id)
    {
        $conexion = $this->conectarBD();

        // Usar sentencia preparada para evitar inyección SQL.
        $eliminar = "DELETE FROM contenido WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $eliminar);
        mysqli_stmt_bind_param($stmt, "i", $id); // 'i' indica que el parámetro es un entero.

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Contenido eliminado correctamente.'); window.location.href='../Vista/admin/contenido.php';</script>";
        } else {
            echo "Error al eliminar: " . mysqli_error($conexion);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}
?>
