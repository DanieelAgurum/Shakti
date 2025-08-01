<?php
date_default_timezone_set('America/Mexico_City');

class Testimonios
{
    private $db;


    public function conectarBD()
    {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=shakti", "root", "", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ]);
            return $pdo;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function __construct($db)
    {
        $this->db = $db;
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
            'enseño mi pene',
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
            'traba',
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
    public function guardarTestimonio($usuariaId, $calificacion, $opinion)
    {
        if ($this->contieneMalasPalabrasPersonalizado($opinion)) {
            return false;
        }

        $sql = "INSERT INTO testimonios (id_usuaria, calificacion, opinion)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE calificacion = VALUES(calificacion), opinion = VALUES(opinion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuariaId, $calificacion, $opinion]);
    }

    public function obtenerTestimonios()
    {
        $sql = "SELECT 
                t.*, 
                CONCAT(u.nombre, ' ', u.apellidos) AS nombre,
                u.foto AS foto
            FROM testimonios t
            INNER JOIN usuarias u ON t.id_usuaria = u.id
            ORDER BY t.fecha DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTestimonioPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM testimonios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
