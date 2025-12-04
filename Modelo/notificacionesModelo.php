<?php
class Notificacion
{
    private static function conectar()
    {
        return new PDO(
            "mysql:host=localhost;dbname=shakti;charset=utf8mb4",
            "root",
            "",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    }

    public static function crearDesdePublicacion($id_usuaria_origen, $id_publicacion = null)
    {
        if (!$id_publicacion) return;

        $conn = self::conectar();

        $stmtPub = $conn->prepare("SELECT anonima FROM publicacion WHERE id_publicacion = :id");
        $stmtPub->execute(['id' => $id_publicacion]);
        $publicacion = $stmtPub->fetch();

        if (!$publicacion) return;

        $esAnonima = $publicacion['anonima'] == 1;

        $nombreOrigen = $esAnonima ? "Anónimo" : null;
        if (!$esAnonima) {
            $stmtNombre = $conn->prepare("SELECT nombre FROM usuarias WHERE id = :id");
            $stmtNombre->execute(['id' => $id_usuaria_origen]);
            $usuariaOrigen = $stmtNombre->fetch();
            $nombreOrigen = $usuariaOrigen ? $usuariaOrigen['nombre'] : "Anónimo";
        }

        $stmt = $conn->prepare("SELECT id FROM usuarias WHERE id != :id_origen");
        $stmt->execute(['id_origen' => $id_usuaria_origen]);
        $usuarias = $stmt->fetchAll();

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

    public static function crearDesdeComentario($id_usuaria_origen, $id_publicacion, $id_comentario, $id_padre = null)
    {
        $conn = self::conectar();

        $stmtNombre = $conn->prepare("SELECT nombre FROM usuarias WHERE id = :id");
        $stmtNombre->execute(['id' => $id_usuaria_origen]);
        $usuariaOrigen = $stmtNombre->fetch();
        $nombreOrigen = $usuariaOrigen ? $usuariaOrigen['nombre'] : "Anónimo";

        if ($id_padre !== null) {
            $stmt = $conn->prepare("SELECT id_usuaria FROM comentarios WHERE id_comentario = :id");
            $stmt->execute(['id' => $id_padre]);
            $comentarioPadre = $stmt->fetch();

            if ($comentarioPadre && $comentarioPadre['id_usuaria'] != $id_usuaria_origen) {
                $mensaje = "$nombreOrigen respondió a tu comentario";
                $stmtInsert = $conn->prepare("
                    INSERT INTO notificaciones (id_usuaria_origen, id_usuaria_destino, id_publicacion, mensaje, fecha_creacion)
                    VALUES (:origen, :destino, :publicacion, :mensaje, NOW())
                ");
                $stmtInsert->execute([
                    'origen' => $id_usuaria_origen,
                    'destino' => $comentarioPadre['id_usuaria'],
                    'publicacion' => $id_publicacion,
                    'mensaje' => $mensaje
                ]);
            }
        } else {
            $stmtAutor = $conn->prepare("SELECT id_usuarias, anonima FROM publicacion WHERE id_publicacion = :id");
            $stmtAutor->execute(['id' => $id_publicacion]);
            $pub = $stmtAutor->fetch();

            if ($pub && $pub['id_usuarias'] != $id_usuaria_origen) {
                $nombreDestinataria = $pub['anonima'] ? "Anónimo" : null;
                if (!$pub['anonima']) {
                    $stmtNombreDest = $conn->prepare("SELECT nombre FROM usuarias WHERE id = :id");
                    $stmtNombreDest->execute(['id' => $pub['id_usuarias']]);
                    $usuariaDest = $stmtNombreDest->fetch();
                    $nombreDestinataria = $usuariaDest ? $usuariaDest['nombre'] : "Usuaria";
                }
                $mensaje = "$nombreOrigen comentó tu publicación";
                $stmtInsert = $conn->prepare("
                    INSERT INTO notificaciones (id_usuaria_origen, id_usuaria_destino, id_publicacion, mensaje, fecha_creacion)
                    VALUES (:origen, :destino, :publicacion, :mensaje, NOW())
                ");
                $stmtInsert->execute([
                    'origen' => $id_usuaria_origen,
                    'destino' => $pub['id_usuarias'],
                    'publicacion' => $id_publicacion,
                    'mensaje' => $mensaje
                ]);
            }
        }
    }

    public static function obtenerParaUsuaria($id_usuaria)
    {
        $conn = self::conectar();
        $stmt = $conn->prepare("
        SELECT 
            n.*, 
            p.anonima AS anonima_publicacion, 
            u.nombre AS nombre_origen,
            CASE
                WHEN n.mensaje LIKE '%comentó%' OR n.mensaje LIKE '%respondió%' THEN 'comentario'
                ELSE 'publicacion'
            END AS tipo_notificacion
        FROM notificaciones n
        LEFT JOIN publicacion p ON n.id_publicacion = p.id_publicacion
        LEFT JOIN usuarias u ON n.id_usuaria_origen = u.id
        WHERE n.id_usuaria_destino = :id 
        ORDER BY n.fecha_creacion DESC
    ");
        $stmt->execute(['id' => $id_usuaria]);
        $notificaciones = $stmt->fetchAll();

        foreach ($notificaciones as &$n) {
            if ($n['tipo_notificacion'] === 'publicacion') {
                $n['mensaje'] = "Nueva publicación agregada por " . ($n['anonima_publicacion'] == 1 ? "Anónimo" : $n['nombre_origen']);
            }
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
