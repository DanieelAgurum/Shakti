<?php
date_default_timezone_set('America/Mexico_City');

class Contenido
{
    private $con;
    private $titulo;
    private $descripcion;
    private $cuerpo_html;
    private $tipo;
    private $url_contenido;
    private $archivo;
    private $imagen1;
    private $imagen2;
    private $imagen3;
    private $nueva_url_contenido;
    private $categoria;
    private $tipo_autor;
    private $id_usuario;
    private $estado;
    private $thumbnail;

    public function conectarBD()
    {
        $this->con = mysqli_connect("localhost", "root", "", "shakti");
        if (!$this->con) {
            die("Problemas con la conexión a la base de datos: " . mysqli_connect_error());
        }
    }

    private function leerArchivo($archivo)
    {
        if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
            return file_get_contents($archivo['tmp_name']);
        }
        return null;
    }

    public function inicializar($titulo, $descripcion, $cuerpo_html, $tipo, $url_contenido, $archivo, $imagen1, $imagen2, $imagen3, $categoria, $tipo_autor, $id_usuario, $estado, $thumbnail)
    {
        $this->titulo        = htmlspecialchars(trim($titulo));
        $this->descripcion   = htmlspecialchars(trim($descripcion));
        $this->cuerpo_html   = $cuerpo_html ?? '';
        $this->tipo          = $tipo;
        $this->url_contenido = $url_contenido ?? '';
        $this->archivo       = is_array($archivo) ? $this->leerArchivo($archivo) : $archivo;
        $this->imagen1       = is_array($imagen1) ? $this->leerArchivo($imagen1) : $imagen1;
        $this->imagen2       = is_array($imagen2) ? $this->leerArchivo($imagen2) : $imagen2;
        $this->imagen3       = is_array($imagen3) ? $this->leerArchivo($imagen3) : $imagen3;
        $this->categoria     = htmlspecialchars(trim($categoria));
        $this->tipo_autor    = $tipo_autor;
        $this->id_usuario    = intval($id_usuario);
        $this->estado        = intval($estado);
        $this->thumbnail     = $thumbnail;
    }

    public function agregarContenido()
    {
        $this->conectarBD();

        $stmt = $this->con->prepare("INSERT INTO contenidos 
        (titulo, descripcion, cuerpo_html, tipo, url_contenido, archivo, imagen1, imagen2, imagen3, categoria, tipo_autor, id_usuario, estado, thumbnail, fecha_publicacion)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        if (!$stmt) {
            die("Error en la preparación: " . $this->con->error);
        }

        $stmt->bind_param(
            "sssssssssssiis",
            $this->titulo,
            $this->descripcion,
            $this->cuerpo_html,
            $this->tipo,
            $this->url_contenido,
            $this->archivo,
            $this->imagen1,
            $this->imagen2,
            $this->imagen3,
            $this->categoria,
            $this->tipo_autor,
            $this->id_usuario,
            $this->estado,
            $this->thumbnail
        );

        if ($this->archivo !== null) {
            $stmt->send_long_data(5, $this->archivo);
        }

        if ($this->archivo !== null) $stmt->send_long_data(5, $this->archivo);
        if ($this->imagen1 !== null) $stmt->send_long_data(6, $this->imagen1);
        if ($this->imagen2 !== null) $stmt->send_long_data(7, $this->imagen2);
        if ($this->imagen3 !== null) $stmt->send_long_data(8, $this->imagen3);

        $resultado = $stmt->execute();
        $stmt->close();

        if ($resultado) {
            return true;
        } else {
            die("Error al insertar el contenido: " . $this->con->error);
        }
    }

    public function editarContenido($id_contenido, $titulo, $descripcion, $cuerpo_html, $nueva_url_contenido, $categoria, $thumbnail, $imagen1, $imagen2, $imagen3, $estado, $archivo = null)
    {
        $this->conectarBD();
        $nuevoArchivo = $this->leerArchivo($archivo);
        $nuevaImagen1 = $this->leerArchivo($imagen1);
        $nuevaImagen2 = $this->leerArchivo($imagen2);
        $nuevaImagen3 = $this->leerArchivo($imagen3);


        if ($nuevoArchivo !== null) {
            $stmt = $this->con->prepare("UPDATE contenidos 
                SET titulo = ?, descripcion = ?, categoria = ?, 
                    thumbnail = ?, estado = ?, archivo = ?
                WHERE id_contenido = ?");
            $stmt->bind_param(
                "ssssssi",
                $titulo,
                $descripcion,
                $categoria,
                $thumbnail,
                $estado,
                $nuevoArchivo,
                $id_contenido
            );
            $stmt->send_long_data(5, $nuevoArchivo);
        } elseif ($nuevaImagen1 !== null || $nuevaImagen2 !== null || $nuevaImagen3 !== null) {
            $stmt = $this->con->prepare("UPDATE contenidos 
                SET titulo = ?, descripcion = ?, cuerpo_html = ?, categoria = ?, 
                    thumbnail = ?, imagen1 = ?, imagen2 = ?, imagen3 = ?, estado = ?
                WHERE id_contenido = ?");
            $stmt->bind_param(
                "sssssssssi",
                $titulo,
                $descripcion,
                $cuerpo_html,
                $categoria,
                $thumbnail,
                $nuevaImagen1,
                $nuevaImagen2,
                $nuevaImagen3,
                $estado,
                $id_contenido
            );
            
            if ($nuevaImagen1 !== null) $stmt->send_long_data(5, $nuevaImagen1);
            if ($nuevaImagen2 !== null) $stmt->send_long_data(6, $nuevaImagen2);
            if ($nuevaImagen3 !== null) $stmt->send_long_data(7,    $nuevaImagen3);

        } elseif ($nueva_url_contenido !== null) {
            $stmt = $this->con->prepare("UPDATE contenidos 
                SET titulo = ?, descripcion = ?, url_contenido = ?, categoria = ?, 
                    thumbnail = ?, estado = ?
                WHERE id_contenido = ?");
            $stmt->bind_param(
                "ssssssi",
                $titulo,
                $descripcion,
                $nueva_url_contenido,
                $categoria,
                $thumbnail,
                $estado,
                $id_contenido
            );
        }

        if (!$stmt) {
            die("Error en la preparación: " . $this->con->error);
        }

        $resultado = $stmt->execute();
        $stmt->close();

        if ($resultado) {
            return true;
        } else {
            die("Error al actualizar el contenido: " . $this->con->error);
        }
    }

    public function eliminarContenido($id_contenido)
    {
        $this->conectarBD();
        $stmt = $this->con->prepare("DELETE FROM contenidos WHERE id_contenido = ?");
        if (!$stmt) {
            die("Error en la preparación: " . $this->con->error);
        }

        $stmt->bind_param("i", $id_contenido);
        $resultado = $stmt->execute();
        $stmt->close();

        if ($resultado) {
            return true;
        } else {
            die("Error al eliminar el contenido: " . $this->con->error);
        }
    }

    public function cambiarEstado($id_contenido, $nuevoEstado)
    {
        $this->conectarBD();
        $stmt = $this->con->prepare("UPDATE contenidos SET estado = ? WHERE id_contenido = ?");
        if (!$stmt) {
            die("Error en la preparación: " . $this->con->error);
        }

        $stmt->bind_param("ii", $nuevoEstado, $id_contenido);
        $resultado = $stmt->execute();
        $stmt->close();

        if ($resultado) {
            return true;
        } else {
            die("Error al cambiar el estado del contenido: " . $this->con->error);
        }
    }

    public function obtenerContenidos()
    {
        $this->conectarBD();
        $sql = "SELECT id_contenido, titulo, descripcion, tipo, url_contenido, categoria, id_usuario, estado, thumbnail, fecha_publicacion 
                FROM contenidos ORDER BY fecha_publicacion DESC";
        $resultado = mysqli_query($this->con, $sql);

        if (!$resultado) {
            die("Error al obtener contenidos: " . $this->con->error);
        }

        $contenidos = [];
        while ($row = mysqli_fetch_assoc($resultado)) {
            $contenidos[] = $row;
        }

        return $contenidos;
    }

    public function obtenerPorId($id_contenido)
    {
        $this->conectarBD();
        $stmt = $this->con->prepare("SELECT * FROM contenidos WHERE id_contenido = ?");
        if (!$stmt) {
            die("Error en la preparación: " . $this->con->error);
        }

        $stmt->bind_param("i", $id_contenido);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            return null;
        }

        return $resultado->fetch_assoc();
    }
}
