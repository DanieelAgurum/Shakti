<?php
date_default_timezone_set('America/Mexico_City');
class Comentario
{
    private function conectarBD()
    {
        $con = mysqli_connect("localhost", "root", "", "shakti");
        if (!$con) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Error de conexión a la base de datos'
            ]);
            exit;
        }
        return $con;
    }

    function normalizarTexto($texto)
    {
        $mapa = [
            '4' => 'a',
            '@' => 'a',
            '3' => 'e',
            '1' => 'i',
            '!' => 'i',
            '0' => 'o',
            '5' => 's',
            '$' => 's',
            '7' => 't',
        ];

        $acentos = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'à' => 'a',
            'è' => 'e',
            'ì' => 'i',
            'ò' => 'o',
            'ù' => 'u',
            'ä' => 'a',
            'ë' => 'e',
            'ï' => 'i',
            'ö' => 'o',
            'ü' => 'u',
            'â' => 'a',
            'ê' => 'e',
            'î' => 'i',
            'ô' => 'o',
            'û' => 'u',
            'Á' => 'a',
            'É' => 'e',
            'Í' => 'i',
            'Ó' => 'o',
            'Ú' => 'u',
            'À' => 'a',
            'È' => 'e',
            'Ì' => 'i',
            'Ò' => 'o',
            'Ù' => 'u',
            'Ä' => 'a',
            'Ë' => 'e',
            'Ï' => 'i',
            'Ö' => 'o',
            'Ü' => 'u',
            'Â' => 'a',
            'Ê' => 'e',
            'Î' => 'i',
            'Ô' => 'o',
            'Û' => 'u',
        ];

        $texto = mb_strtolower($texto);
        $texto = strtr($texto, $mapa);
        $texto = strtr($texto, $acentos);
        $texto = preg_replace('/[^\p{L}\p{N}\s]/u', '', $texto);
        $texto = preg_replace('/(.)\1{2,}/', '$1', $texto);

        return $texto;
    }
    public function contieneContextoOfensivo($contenido)
    {
        $contextosOfensivos = [

            // === Sexual explícito ===
            'meterte el pene',
            'te falta pene',
            'chupa mi pene',
            'te doy con el pene',
            'pene chico',
            'pene grande',
            'enséñame tu pene',
            'metetelo por el culo',
            'abre las piernas',
            'muestra las tetas',
            'tienes buenas tetas',
            'quiero cogerte',
            'te voy a coger',
            'me calientas',
            'eres mi puta',
            'estás buena para coger',
            'te voy a reventar',
            'me haces una paja',
            'quiero verte desnuda',
            'te quiero desnuda',
            'te quiero encuerada',
            'te la meto',
            'te lo meto',

            // === Violencia / amenazas ===
            'te odio',
            'te van a matar',
            'te van a violar',
            'te voy a cortar',
            'te voy a matar',
            'te voy a quemar',
            'te voy a rajar',
            'te voy a romper la cara',
            'te voy a violar',
            'te voy a partir la madre',
            'me das asco',
            'eres basura',
            'vales menos que nada',
            'ojalá te violen',
            'te mereces morir',
            'te deberían golpear',
            'si fuera tu novio te daría una madriza',
            'me cago en ti',
            'me cago en tu madre',
            'me importa un carajo',
            'me la chupas',
            'no sirves para nada',
            'no tienes futuro',
            'no vales nada',
            'no vales verga',
            'ojalá sufras',
            'ojalá te maten',
            'ojalá te mueras',

            // === Machismo / Misoginia ===
            'las mujeres no sirven',
            'las mujeres son tontas',
            'las mujeres solo sirven para',
            'calla mujer',
            'vete a lavar los platos',
            'por eso no te quiere nadie',
            'te lo buscaste por vestirte así',
            'seguro lo disfrutaste',
            'todas son unas putas',
            'todas son iguales',
            'por eso te pegan',
            'se lo merece por zorra',

            // === Racismo / Discriminación ===
            'negra de mierda',
            'india asquerosa',
            'sudaca de mierda',
            'te pareces a un mono',
            'pareces un chimpancé',
            'cara de chango',
            'vete a tu país',
            'maldita prieta',
            'maldita negra',

            // === Autolesión / Incitación al suicidio ===
            'mátate',
            'suicídate',
            'nadie te quiere',
            'deberías desaparecer',
            'eres una carga',
            'córtate las venas',
            'mátate ya',

            // === LGTBfobia / Transfobia ===
            'pareces maricón',
            'te comportas como una machorra',
            'por eso eres gay',
            'pinche joto',
            'lesbiana de mierda',
            'pareces travesti',
            'eso te pasa por no ser normal',

            // === Frases comunes de agresión o desdén ===
            'vete a chingar a tu madre',
            'vete a la chingada',
            'vete a la chingada madre',
            'vete a la mierda',
            'vete a la verga',
            'vete al diablo',
        ];

        $contenido_normalizado = $this->normalizarTexto($contenido);

        foreach ($contextosOfensivos as $frase) {
            if (mb_stripos($contenido_normalizado, $frase) !== false) {
                return true;
            }
        }

        return false;
    }

    public function contieneMalasPalabrasPersonalizado($contenido)
    {
        $malasPalabras = [
            'anda a cocinar',
            'anda a lavar los platos',
            'animal',
            'arrastrada',
            'arrastrado',
            'asquerosa',
            'asqueroso',
            'babosa',
            'baboso',
            'bastarda',
            'basura',
            'bestia',
            'bruja',
            'cabron',
            'cabrona',
            'cabronazo',
            'cabroncito',
            'callate',
            'callate imbecil',
            'callate la boca',
            'callate puta',
            'callate zorra',
            'carechimba',
            'careculo',
            'careverga',
            'carajo',
            'chinga',
            'chingadazo',
            'chingada',
            'chingada madre',
            'chingado',
            'chingar',
            'chingón',
            'chingue',
            'chupapija',
            'chupapolla',
            'cojones',
            'cojón',
            'coger',
            'cogida',
            'cogerse',
            'coño',
            'coñazo',
            'coñito',
            'culazo',
            'culera',
            'culero',
            'culicagada',
            'culicagado',
            'culo',
            'culito',
            'desgraciada',
            'desgraciado',
            'eres débil',
            'eres un fraude',
            'inútil',
            'estupida',
            'estupidazo',
            'estupidita',
            'estupidito',
            'estupido',
            'fracasada',
            'fracasado',
            'gilipollas',
            'gilipollez',
            'golfa',
            'gonorrea',
            'hijo de puta',
            'huevo',
            'huevazo',
            'huevona',
            'huevón',
            'idiota',
            'idiotazo',
            'idiotita',
            'imbécil',
            'inservible',
            'joder',
            'lacra',
            'lameme',
            'loca de mierda',
            'machorra',
            'malnacida',
            'malnacido',
            'malparida',
            'malparido',
            'mamona',
            'mamón',
            'marica',
            'maricon',
            'mariconazo',
            'mariconcito',
            'matala',
            'matalo',
            'matate',
            'matarse',
            'mierda',
            'mierdita',
            'mierdota',
            'mierdero',
            'mongolo',
            'mongólica',
            'muerete',
            'naco',
            'naca',
            'paja',
            'pedorro',
            'pedo',
            'pendeja',
            'pendejazo',
            'pendejito',
            'pendejo',
            'perra',
            'perra sucia',
            'perrita',
            'pija',
            'pinche',
            'pinche pendejo',
            'pinche puta',
            'pinga',
            'pito',
            'pollón',
            'polla',
            'pta',
            'pto',
            'puta',
            'putada',
            'puto',
            'putas',
            'putazo',
            'putilla',
            'putita',
            'putito',
            'puton',
            'putona',
            'putonazo',
            'que te jodan',
            'retardada',
            'retardado',
            'retrasada mental',
            'subnormal',
            'suicidarse',
            'suicidate',
            'tarada',
            'tarado',
            'tetas',
            'tonta',
            'tonta del culo',
            'tontita',
            'tonto',
            'tontito',
            'travesti',
            'trava',
            'travo',
            'verga',
            'vergona',
            'vergota',
            'verguita',
            'vergón',
            'vieja loca',
            'vieja puta',
            'zorra',
            'zorrilla',
            'zorrita',
            'zorrón',
        ];


        $contenido_normalizado = $this->normalizarTexto($contenido);


        foreach ($malasPalabras as $palabra) {
            if (mb_stripos($contenido_normalizado, $palabra) !== false) {
                return true;
            }
        }

        // Revisión contextual
        if ($this->contieneContextoOfensivo($contenido)) {
            return true;
        }

        return false;
    }

    public function agregarComentario($contenido, $idPublicacion, $idUsuaria, $idPadre = null)
    {
        if ($this->contieneMalasPalabrasPersonalizado($contenido)) {
            return 'malas_palabras';
        }

        $conn = $this->conectarBD();

        if ($idPadre === null) {
            $query = "INSERT INTO comentarios (id_publicacion, id_usuaria, comentario, fecha_comentario) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iis", $idPublicacion, $idUsuaria, $contenido);
        } else {
            $query = "INSERT INTO comentarios (id_publicacion, id_usuaria, comentario, id_padre, fecha_comentario) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iisi", $idPublicacion, $idUsuaria, $contenido, $idPadre);
        }

        if ($stmt->execute()) {
            $idInsertado = $stmt->insert_id;
            $stmt->close();
            $conn->close();
            return $idInsertado;
        } else {
            $stmt->close();
            $conn->close();
            return false;
        }
    }

    public function obtenerComentariosPorPublicacion($idPublicacion)
    {
        $conn = $this->conectarBD();

        $query = "SELECT 
                c.id_comentario, 
                c.comentario, 
                c.fecha_comentario AS fecha, 
                c.id_padre,
                u.nombre 
              FROM comentarios c 
              JOIN usuarias u ON c.id_usuaria = u.id 
              WHERE c.id_publicacion = ? 
              ORDER BY c.fecha_comentario ASC";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idPublicacion);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $comentarios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $comentarios[] = $fila;
        }

        $stmt->close();
        $conn->close();

        return $comentarios;
    }

    public function contarComentariosPorPublicacion($idPublicacion)
    {
        $conn = $this->conectarBD();
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM comentarios WHERE id_publicacion = ?");
        $stmt->bind_param("i", $idPublicacion);
        $stmt->execute();
        $result = $stmt->get_result();
        $total = $result->fetch_assoc()['total'] ?? 0;
        $stmt->close();
        $conn->close();
        return $total;
    }
}
