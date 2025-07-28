<?php
class Notificacion
{
    private static function conectar()
    {
        return new PDO("mysql:host=localhost;dbname=shakti", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public static function crearDesdePublicacion($id_usuaria_origen)
    {
        $conn = self::conectar();

        // Obtener nombre de la usuaria que hizo la publicación
        $stmtNombre = $conn->prepare("SELECT nombre FROM usuarias WHERE id = :id");
        $stmtNombre->execute(['id' => $id_usuaria_origen]);
        $usuariaOrigen = $stmtNombre->fetch();

        if (!$usuariaOrigen) return;

        $nombreOrigen = $usuariaOrigen['nombre'];

        // Obtener todas las demás usuarias
        $stmt = $conn->prepare("SELECT id FROM usuarias WHERE id != :id_origen");
        $stmt->execute(['id_origen' => $id_usuaria_origen]);
        $usuarias = $stmt->fetchAll();

        // Enviar notificaciones a cada una
        foreach ($usuarias as $usuaria) {
            $mensaje = "Nueva publicación agregada por $nombreOrigen";
            $stmtInsert = $conn->prepare("
                INSERT INTO notificaciones (id_usuaria_origen, id_usuaria_destino, mensaje, fecha_creacion) 
                VALUES (:origen, :destino, :mensaje, NOW())
            ");
            $stmtInsert->execute([
                'origen' => $id_usuaria_origen,
                'destino' => $usuaria['id'],
                'mensaje' => $mensaje
            ]);
        }
    }

    public static function obtenerParaUsuaria($id_usuaria)
    {
        $conn = self::conectar();
        $stmt = $conn->prepare("
            SELECT * FROM notificaciones 
            WHERE id_usuaria_destino = :id 
            ORDER BY fecha_creacion DESC
        ");
        $stmt->execute(['id' => $id_usuaria]);
        return $stmt->fetchAll();
    }

    public static function marcarComoLeida($id_notificacion)
    {
        $conn = self::conectar();
        $stmt = $conn->prepare("UPDATE notificaciones SET leida = 1 WHERE id = :id");
        $stmt->execute(['id' => $id_notificacion]);
    }
}
