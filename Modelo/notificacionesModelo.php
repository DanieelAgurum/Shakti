<?php
class Notificacion
{
    private static function conectar()
    {
        return new PDO("mysql:host=localhost;dbname=shakti;charset=utf8mb4", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public static function crearDesdePublicacion($id_usuaria_origen, $id_publicacion = null)
    {
        $conn = self::conectar();

        if (!$id_publicacion) return;

        $stmtPub = $conn->prepare("SELECT anonima FROM publicacion WHERE id_publicacion = :id");
        $stmtPub->execute(['id' => $id_publicacion]);
        $publicacion = $stmtPub->fetch();

        if (!$publicacion) return;

        $esAnonima = $publicacion['anonima'] == 1;

        // Obtener nombre de la usuaria origen solo si no es anónimo
        $nombreOrigen = "Anónimo";
        if (!$esAnonima) {
            $stmtNombre = $conn->prepare("SELECT nombre FROM usuarias WHERE id = :id");
            $stmtNombre->execute(['id' => $id_usuaria_origen]);
            $usuariaOrigen = $stmtNombre->fetch();
            if ($usuariaOrigen) {
                $nombreOrigen = $usuariaOrigen['nombre'];
            }
        }

        // Obtener todas las demás usuarias
        $stmt = $conn->prepare("SELECT id FROM usuarias WHERE id != :id_origen");
        $stmt->execute(['id_origen' => $id_usuaria_origen]);
        $usuarias = $stmt->fetchAll();

        // Crear notificaciones
        foreach ($usuarias as $usuaria) {
            $mensaje = "Nueva publicación agregada por " . $nombreOrigen;
            $stmtInsert = $conn->prepare("
            INSERT INTO notificaciones (id_usuaria_origen, id_usuaria_destino, id_publicacion, mensaje, fecha_creacion)
            VALUES (:origen, :destino, :publicacion, :mensaje, NOW())
        ");
            $stmtInsert->execute([
                'origen' => $id_usuaria_origen,
                'destino' => $usuaria['id'],
                'publicacion' => $id_publicacion,
                'mensaje' => $mensaje
            ]);
        }
    }

    public static function obtenerParaUsuaria($id_usuaria)
    {
        $conn = self::conectar();
        $stmt = $conn->prepare("
        SELECT n.*, p.anonima, u.nombre AS nombre_origen
        FROM notificaciones n
        LEFT JOIN publicacion p ON n.id_publicacion = p.id_publicacion
        LEFT JOIN usuarias u ON n.id_usuaria_origen = u.id
        WHERE n.id_usuaria_destino = :id 
        ORDER BY n.fecha_creacion DESC
    ");
        $stmt->execute(['id' => $id_usuaria]);
        $notificaciones = $stmt->fetchAll();

        // Ajustar mensaje según anonima
        foreach ($notificaciones as &$n) {
            $n['mensaje'] = "Nueva publicación agregada por " . ($n['anonima'] == 1 ? "Anónimo" : $n['nombre_origen']);
        }

        return $notificaciones;
    }

    public static function marcarComoLeida($id_notificacion)
    {
        $conn = self::conectar();
        $stmt = $conn->prepare("UPDATE notificaciones SET leida = 1 WHERE id = :id");
        $stmt->execute(['id' => $id_notificacion]);
    }

    public static function marcarTodasComoLeidas($id_usuaria)
    {
        $conn = self::conectar();
        $stmt = $conn->prepare("UPDATE notificaciones SET leida = 1 WHERE id_usuaria_destino = :id");
        $stmt->execute(['id' => $id_usuaria]);
    }
}
