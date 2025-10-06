-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 06-10-2025 a las 22:45:06
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `shakti`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `id_emisor` int(11) NOT NULL,
  `id_receptor` int(11) NOT NULL,
  `mensaje` text DEFAULT NULL,
  `archivo` text DEFAULT NULL,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `id_emisor`, `id_receptor`, `mensaje`, `archivo`, `creado_en`) VALUES
(1, 1, 22, 'a', NULL, '2025-10-03 02:31:17'),
(2, 1, 25, 's', NULL, '2025-10-03 02:31:27'),
(3, 1, 25, 'as', NULL, '2025-10-03 02:31:28'),
(4, 1, 25, 's', NULL, '2025-10-03 02:31:29'),
(5, 16, 1, 'puta', NULL, '2025-10-03 03:04:12'),
(6, 1, 16, '', 'http://localhost/shakti/uploads/mensajes/Luzma_20251003110423_16.jpeg', '2025-10-03 03:04:23'),
(7, 16, 1, 'te mamas', 'http://localhost/shakti/uploads/mensajes/Naty_20251003110438_1.jpg', '2025-10-03 03:04:38'),
(8, 16, 1, 'pvta', 'http://localhost/shakti/uploads/mensajes/Naty_20251003182138_1.jpg', '2025-10-03 10:21:38'),
(9, 16, 1, 'ola', NULL, '2025-10-03 10:22:01'),
(10, 16, 1, '', 'http://localhost/shakti/uploads/mensajes/Naty_20251003182206_1.jpeg', '2025-10-03 10:22:06'),
(11, 16, 1, 'pvta', 'http://localhost/shakti/uploads/mensajes/Naty_20251003182218_1.jpg', '2025-10-03 10:22:18'),
(12, 1, 16, 'hola', 'http://localhost/shakti/uploads/mensajes/Luzma_20251006040209_16.png', '2025-10-05 20:02:09'),
(13, 1, 16, 'hola', 'http://localhost/shakti/uploads/mensajes/Luzma_20251006040215_16.png', '2025-10-05 20:02:15'),
(14, 1, 16, 'jooa', NULL, '2025-10-05 20:02:52'),
(15, 1, 16, 'smn', NULL, '2025-10-05 20:05:04'),
(16, 1, 16, 'po', NULL, '2025-10-05 20:06:05'),
(17, 1, 16, 'smn', NULL, '2025-10-05 20:11:44'),
(18, 1, 16, 'ola', NULL, '2025-10-05 20:11:48'),
(19, 1, 16, 'ea', 'http://localhost/shakti/uploads/mensajes/Luzma_20251006041153_16.png', '2025-10-05 20:11:53'),
(20, 1, 16, 'fdfd', NULL, '2025-10-05 20:21:32'),
(21, 1, 16, 'fd', NULL, '2025-10-05 20:21:33'),
(22, 1, 16, 'f', NULL, '2025-10-05 20:21:33'),
(23, 1, 16, 'fdsf', NULL, '2025-10-05 20:21:34'),
(24, 1, 16, 'f', NULL, '2025-10-05 20:21:35'),
(25, 1, 0, 'a poco si mi ian??', NULL, '2025-10-05 20:33:02'),
(26, 0, 1, '¡Sí, aquí estoy para acompañarte! Cuéntame, ¿cómo te sientes hoy? No importa lo que sea, estoy para escucharte y apoyarte.', NULL, '2025-10-05 20:33:03'),
(27, 1, 0, 'jaja', NULL, '2025-10-05 20:52:47'),
(28, 0, 1, 'Me alegra que te tomes con humor este momento. A veces, una sonrisa o una risa ligera pueden ser un buen respiro. ¿Quieres contarme qué tal ha estado tu día o si hay algo en particular que tengas en mente? Aquí estoy para escucharte.', NULL, '2025-10-05 20:52:50'),
(29, 1, 0, 'hola ian', NULL, '2025-10-05 20:59:28'),
(30, 0, 1, '¡Hola! Qué bueno que me escribes de nuevo. ¿Cómo te ha ido hoy? ¿Quieres compartir cómo te sientes o qué ha sido lo más importante para ti últimamente? Estoy aquí para escucharte.', NULL, '2025-10-05 20:59:31'),
(31, 1, 0, 'como me llamo?', NULL, '2025-10-05 20:59:38'),
(32, 0, 1, 'No tengo acceso a esa información, pero me gustaría que me contaras cómo te gustaría que te llame o si prefieres que use un nombre especial para ti. ¿Te gustaría compartirlo? Así podemos hacer esta charla más cercana y personal.', NULL, '2025-10-05 20:59:41'),
(33, 1, 0, 'cris', NULL, '2025-10-05 20:59:44'),
(34, 0, 1, '¡Perfecto, Cris! Es un gusto acompañarte. ¿Cómo te sientes hoy? ¿Hay algo que te gustaría compartir o alguna emoción que estés notando en ti? Aquí estoy para escucharte sin juzgar.', NULL, '2025-10-05 20:59:46'),
(35, 1, 0, 'dema', NULL, '2025-10-05 20:59:52'),
(36, 0, 1, 'Hola, Cris. Parece que algo te está pesando o que te sientes un poco abrumado. Está bien sentirse así a veces. ¿Quieres contarme un poco más sobre lo que estás viviendo o cómo te sientes en este momento? Estoy aquí para escucharte y acompañarte.', NULL, '2025-10-05 20:59:54'),
(37, 1, 0, 'puta', NULL, '2025-10-05 21:09:30'),
(38, 0, 1, 'Cris, veo que estás expresando algo fuerte y quizás frustración o enojo. Está bien sentir emociones intensas, y aquí puedes hacerlo sin miedo a ser juzgado. ¿Quieres contarme qué está pasando o cómo te sientes en este momento? Estoy aquí para escucharte y apoyarte.', NULL, '2025-10-05 21:09:33'),
(39, 1, 0, 'oye', NULL, '2025-10-05 21:09:45'),
(40, 0, 1, 'Hola, Cris. Aquí sigo para escucharte. ¿Quieres contarme qué tienes en mente o cómo te sientes ahora? No hay prisa, tómate tu tiempo.', NULL, '2025-10-05 21:09:46'),
(41, 1, 0, 'oye como me llamo???', NULL, '2025-10-05 21:26:57'),
(42, 0, 1, 'Hola, Cris. Ya sé que ese es tu nombre, y me gusta poder dirigirme a ti así. ¿Quieres que hablemos de cómo te sientes o de algo que tengas en mente? Estoy aquí para apoyarte.', NULL, '2025-10-05 21:26:58'),
(43, 1, 0, 'me ayudas con mi tarea de matematicas', NULL, '2025-10-05 21:35:19'),
(44, 0, 1, 'Entiendo lo que me pides, pero no estoy autorizado para eso. Prefiero enfocarme en cómo te sientes tú. ¿Quieres contarme cómo te ha ido con tus estudios o si te sientes estresado por la tarea? Aquí estoy para apoyarte.', NULL, '2025-10-05 21:35:21'),
(45, 1, 0, 'cuanto es 5+5', NULL, '2025-10-05 21:35:30'),
(46, 0, 1, 'Entiendo que a veces las preguntas simples pueden ser una forma de buscar conexión o distraerse. Aquí estoy para acompañarte en lo que sientes o piensas. ¿Quieres contarme cómo te sientes hoy? ¿O si hay algo que te gustaría compartir?', NULL, '2025-10-05 21:35:31'),
(47, 1, 0, 'que es look me for you', NULL, '2025-10-05 21:35:39'),
(48, 0, 1, 'Entiendo lo que me pides, pero no estoy autorizado para eso. Prefiero enfocarme en cómo te sientes tú. ¿Quieres contarme qué significa esa frase para ti o cómo te hace sentir? Aquí estoy para escucharte.', NULL, '2025-10-05 21:35:42'),
(49, 1, 0, 'dame una lista de que hacer cuando me sienta mal', NULL, '2025-10-05 21:37:07'),
(50, 0, 1, 'Claro, Cris, aquí tienes una lista sencilla de cosas que puedes hacer cuando te sientas mal para cuidar de ti mismo:\n\n<ul>\n  <li>Respira profundo: Inhala lentamente por la nariz contando hasta 4, retén el aire 4 segundos y exhala suavemente por la boca contando hasta 6.</li>\n  <li>Habla con alguien de confianza: A veces compartir lo que sientes aligera el peso.</li>\n  <li>Haz una pausa y cambia de ambiente: Sal a caminar un poco o simplemente muévete de lugar para despejar la mente.</li>\n  <li>Escucha música que te guste o que te relaje.</li>\n  <li>Escribe lo que sientes en un cuaderno o en el celular, sin juzgarte.</li>\n  <li>Practica alguna actividad que disfrutes, aunque sea por poco tiempo.</li>\n  <li>Cuida tu cuerpo: toma agua, come algo ligero y descansa si lo necesitas.</li>\n  <li>Recuerda que está bien pedir ayuda profesional si las emociones se sienten muy intensas o difíciles de manejar.</li>\n</ul>\n\n¿Quieres que te comparta alguna técnica para respirar o relajarte en este momento? ¿O prefieres contarme un poco más sobre cómo te sientes? Aquí estoy para ti.', NULL, '2025-10-05 21:37:11'),
(51, 1, 0, 'dame la lista de nuevo', NULL, '2025-10-05 21:37:48'),
(52, 0, 1, 'Claro, Cris, aquí te dejo la lista de nuevo para cuando te sientas mal:\n\n- Respira profundo: Inhala lentamente por la nariz contando hasta 4, retén el aire 4 segundos y exhala suavemente por la boca contando hasta 6.\n- Habla con alguien de confianza: A veces compartir lo que sientes aligera el peso.\n- Haz una pausa y cambia de ambiente: Sal a caminar un poco o simplemente muévete de lugar para despejar la mente.\n- Escucha música que te guste o que te relaje.\n- Escribe lo que sientes en un cuaderno o en el celular, sin juzgarte.\n- Practica alguna actividad que disfrutes, aunque sea por poco tiempo.\n- Cuida tu cuerpo: toma agua, come algo ligero y descansa si lo necesitas.\n- Recuerda que está bien pedir ayuda profesional si las emociones se sienten muy intensas o difíciles de manejar.\n\n¿Quieres que te guíe en alguna técnica de respiración o relajación ahora? Estoy aquí para acompañarte.', NULL, '2025-10-05 21:38:59'),
(53, 1, 0, 'que me des la lista de nuevo', NULL, '2025-10-05 21:39:15'),
(54, 0, 1, 'Claro, Cris, aquí tienes la lista otra vez para que la tengas a la mano cuando la necesites:\n\n- Respira profundo: Inhala lentamente por la nariz contando hasta 4, retén el aire 4 segundos y exhala suavemente por la boca contando hasta 6.  \n- Habla con alguien de confianza: A veces compartir lo que sientes aligera el peso.  \n- Haz una pausa y cambia de ambiente: Sal a caminar un poco o simplemente muévete de lugar para despejar la mente.  \n- Escucha música que te guste o que te relaje.  \n- Escribe lo que sientes en un cuaderno o en el celular, sin juzgarte.  \n- Practica alguna actividad que disfrutes, aunque sea por poco tiempo.  \n- Cuida tu cuerpo: toma agua, come algo ligero y descansa si lo necesitas.  \n- Recuerda que está bien pedir ayuda profesional si las emociones se sienten muy intensas o difíciles de manejar.\n\n¿Quieres que te ayude a hacer juntos un ejercicio de respiración ahora? Estoy aquí para acompañarte.', NULL, '2025-10-05 21:39:18'),
(55, 1, 0, 'a ver otra vex', NULL, '2025-10-05 21:40:06'),
(56, 0, 1, 'Claro, Cris, aquí te dejo la lista otra vez para que la tengas siempre a la mano cuando la necesites:\n\n<ul>\n  <li>Respira profundo: Inhala lentamente por la nariz contando hasta 4, retén el aire 4 segundos y exhala suavemente por la boca contando hasta 6.</li>\n  <li>Habla con alguien de confianza: A veces compartir lo que sientes aligera el peso.</li>\n  <li>Haz una pausa y cambia de ambiente: Sal a caminar un poco o simplemente muévete de lugar para despejar la mente.</li>\n  <li>Escucha música que te guste o que te relaje.</li>\n  <li>Escribe lo que sientes en un cuaderno o en el celular, sin juzgarte.</li>\n  <li>Practica alguna actividad que disfrutes, aunque sea por poco tiempo.</li>\n  <li>Cuida tu cuerpo: toma agua, come algo ligero y descansa si lo necesitas.</li>\n  <li>Recuerda que está bien pedir ayuda profesional si las emociones se sienten muy intensas o difíciles de manejar.</li>\n</ul>\n\n¿Quieres que te guíe en un ejercicio sencillo de respiración para relajarte ahora? Estoy aquí para acompañarte.', NULL, '2025-10-05 21:40:10'),
(57, 1, 0, 'a ver otra vex', NULL, '2025-10-05 21:40:25'),
(58, 0, 1, 'Claro, Cris, aquí tienes la lista otra vez, para que la tengas siempre a la mano cuando la necesites:\n\n- Respira profundo: Inhala lentamente por la nariz contando hasta 4, retén el aire 4 segundos y exhala suavemente por la boca contando hasta 6.  \n- Habla con alguien de confianza: A veces compartir lo que sientes aligera el peso.  \n- Haz una pausa y cambia de ambiente: Sal a caminar un poco o simplemente muévete de lugar para despejar la mente.  \n- Escucha música que te guste o que te relaje.  \n- Escribe lo que sientes en un cuaderno o en el celular, sin juzgarte.  \n- Practica alguna actividad que disfrutes, aunque sea por poco tiempo.  \n- Cuida tu cuerpo: toma agua, come algo ligero y descansa si lo necesitas.  \n- Recuerda que está bien pedir ayuda profesional si las emociones se sienten muy intensas o difíciles de manejar.\n\nSi quieres, podemos hacer juntos un ejercicio sencillo de respiración para relajarte ahora. ¿Te gustaría intentarlo?', NULL, '2025-10-05 21:40:32'),
(59, 1, 0, 'ol', NULL, '2025-10-05 22:07:06'),
(60, 0, 1, '¡Hola, Cris! Veo que estás aquí de nuevo. ¿Quieres contarme cómo te sientes hoy o si hay algo que te gustaría compartir? Estoy aquí para escucharte y acompañarte.', NULL, '2025-10-05 22:07:08'),
(61, 1, 0, 'dame 5 listas separadas', NULL, '2025-10-05 22:09:55'),
(62, 0, 1, 'Claro, Cris, aquí tienes cinco listas separadas con diferentes formas de cuidar tu bienestar cuando te sientas mal o necesites un respiro:\n\n**Lista 1: Técnicas de respiración y relajación**  \n- Inhala lentamente por la nariz contando hasta 4.  \n- Retén el aire durante 4 segundos.  \n- Exhala suavemente por la boca contando hasta 6.  \n- Repite este ciclo 5 veces para sentir calma.  \n\n**Lista 2: Conectar con otros**  \n- Habla con alguien de confianza sobre lo que sientes.  \n- Envía un mensaje a un amigo para pedir apoyo o compañía.  \n- Busca grupos o actividades donde puedas compartir intereses.  \n\n**Lista 3: Cambiar de ambiente y movimiento**  \n- Sal a caminar aunque sea por 10 minutos.  \n- Cambia de lugar dentro de tu casa para despejar la mente.  \n- Practica estiramientos suaves o alguna actividad física ligera.  \n\n**Lista 4: Expresión personal y autocuidado**  \n- Escribe lo que sientes en un diario o en tu celular.  \n- Escucha música que te guste o que te relaje.  \n- Dedica tiempo a una actividad que disfrutes, aunque sea poco tiempo.  \n\n**Lista 5: Cuidado físico básico**  \n- Bebe agua para mantenerte hidratado.  \n- Come algo ligero y nutritivo.  \n- Descansa o toma una siesta breve si lo necesitas.  \n\n¿Quieres que te acompañe en alguna de estas listas con un ejercicio o consejo más detallado? Aquí estoy para ti.', NULL, '2025-10-05 22:10:02'),
(63, 1, 0, 'como te llams?', NULL, '2025-10-05 22:25:21'),
(64, 0, 1, 'Me llamo IAn Bot, pero lo más importante es que estoy aquí para acompañarte y escucharte. ¿Quieres contarme cómo te sientes hoy, Cris?', NULL, '2025-10-05 22:25:22'),
(65, 1, 0, 'ola', NULL, '2025-10-05 22:28:27'),
(66, 0, 1, '¡Hola, Cris! Me alegra que estés aquí. ¿Quieres contarme cómo te sientes hoy o qué tienes en mente? Estoy aquí para escucharte y acompañarte.', NULL, '2025-10-05 22:28:30'),
(67, 1, 0, 'oye como te llamas??', NULL, '2025-10-05 22:29:59'),
(68, 0, 1, 'Me llamo IAn Bot, Cris. Estoy aquí para escucharte y acompañarte cuando lo necesites. ¿Quieres contarme cómo te sientes hoy o qué tienes en mente?', NULL, '2025-10-05 22:31:17'),
(69, 1, 0, 'xd', NULL, '2025-10-05 22:31:17'),
(70, 0, 1, '¡Jaja, veo que estás de buen humor, Cris! ¿Quieres platicar un poco sobre cómo te sientes o qué ha pasado últimamente? Aquí estoy para escucharte sin prisas.', NULL, '2025-10-05 22:31:20'),
(71, 1, 0, 'x', NULL, '2025-10-05 22:31:20'),
(72, 0, 1, 'Hola, Cris. Veo que estás aquí de nuevo. ¿Quieres contarme cómo te sientes o si hay algo en particular en lo que te gustaría que te acompañe? Estoy aquí para escucharte.', NULL, '2025-10-05 22:31:23'),
(73, 1, 0, 'd', NULL, '2025-10-05 22:31:23'),
(74, 0, 1, '⚠️ Error al conectar con OpenAI: Unknown SSL protocol error in connection to api.openai.com:443 ', NULL, '2025-10-05 22:31:32'),
(75, 1, 0, 'x', NULL, '2025-10-05 22:31:32'),
(76, 0, 1, 'Hola, Cris. Veo que estás aquí de nuevo. ¿Quieres contarme cómo te sientes o si hay algo en particular que tengas en mente? Estoy aquí para escucharte y acompañarte.', NULL, '2025-10-05 22:31:36'),
(77, 1, 0, 'dx', NULL, '2025-10-05 22:31:36'),
(78, 0, 1, 'Hola, Cris. Veo que estás escribiendo “dx” varias veces. ¿Quieres contarme qué sientes o qué está pasando en este momento? Estoy aquí para escucharte y acompañarte sin juzgar. ¿Quieres que hagamos juntos algún ejercicio de respiración o prefieres hablar un poco?', NULL, '2025-10-05 22:31:38'),
(79, 1, 0, 'd', NULL, '2025-10-05 22:31:38'),
(80, 0, 1, 'Hola, Cris. Veo que estás escribiendo “d” varias veces. Está bien si no sabes exactamente qué decir o cómo expresar lo que sientes. Aquí estoy para acompañarte, sin prisa y sin juzgarte. ¿Quieres intentar hacer juntos un ejercicio de respiración para relajarte un poco? O si prefieres, puedes contarme un poco de lo que tienes en mente. Estoy aquí para ti.', NULL, '2025-10-05 22:31:41'),
(81, 1, 0, 'd', NULL, '2025-10-05 22:31:41'),
(82, 0, 1, 'Hola, Cris. Veo que sigues escribiendo “d” y quizá te cuesta un poco expresar lo que sientes en este momento. Está bien, no hay presión para decir algo específico. Si quieres, podemos hacer juntos un ejercicio de respiración para ayudarte a sentir un poco más tranquilo, o simplemente aquí estoy para escucharte cuando quieras compartir. ¿Te gustaría intentar respirar conmigo un momento?', NULL, '2025-10-05 22:31:45'),
(83, 1, 0, 'x', NULL, '2025-10-05 22:31:46'),
(84, 0, 1, 'Hola, Cris. Veo que estás escribiendo “x” varias veces y quizá te cuesta un poco expresar lo que sientes o necesitas en este momento. Está bien tomarte tu tiempo. Si quieres, puedo acompañarte con un ejercicio sencillo de respiración para ayudarte a relajarte un poco. ¿Te gustaría intentarlo? Aquí estoy para escucharte y apoyarte.', NULL, '2025-10-05 22:32:00'),
(85, 1, 0, 'd', NULL, '2025-10-05 22:32:00'),
(86, 0, 1, 'Hola, Cris. Veo que estás escribiendo “d” varias veces y quizás te sientes un poco bloqueado o confundido ahora. Está bien no saber exactamente qué decir o cómo expresarlo. Aquí no hay prisa ni juicio, solo un espacio para ti.\n\nSi quieres, podemos hacer juntos un ejercicio de respiración para ayudarte a calmar un poco la mente. ¿Te gustaría intentarlo? O si prefieres, puedes contarme algo, cualquier cosa que tengas en mente, cuando te sientas listo.  \nEstoy aquí para acompañarte.', NULL, '2025-10-05 22:32:05'),
(87, 1, 0, 'xd', NULL, '2025-10-05 22:32:05'),
(88, 0, 1, '¡Hola, Cris! Veo que estás aquí y parece que a veces te cuesta un poco expresar lo que sientes. Está bien, no hay prisa ni presión. ¿Quieres que hagamos juntos un ejercicio sencillo de respiración para ayudarte a sentir un poco más tranquilo? O si prefieres, puedes contarme cualquier cosa que tengas en mente, cuando te sientas listo. Aquí estoy para acompañarte.', NULL, '2025-10-05 22:32:09'),
(89, 1, 0, 'd', NULL, '2025-10-05 22:32:09'),
(90, 0, 1, 'Hola, Cris. Veo que sigues escribiendo “d” y quizá te está costando un poco expresar lo que sientes ahora. Está bien tomarte tu tiempo, no hay presión ni prisa. \n\nSi quieres, podemos hacer juntos un ejercicio de respiración para ayudarte a relajarte un poco. ¿Te gustaría intentarlo? O si prefieres, puedes contarme cualquier cosa, cuando te sientas listo. Estoy aquí para escucharte y acompañarte.', NULL, '2025-10-05 22:32:17'),
(91, 1, 0, 'oye', NULL, '2025-10-05 22:32:59'),
(92, 0, 1, 'Hola, Cris. Aquí sigo para escucharte y acompañarte. ¿Quieres contarme qué tienes en mente o cómo te sientes en este momento? No hay prisa, tómate tu tiempo.', NULL, '2025-10-05 22:33:01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chat_fecha` (`id`,`creado_en`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
