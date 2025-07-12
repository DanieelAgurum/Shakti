-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 12-07-2025 a las 23:50:22
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
-- Estructura de tabla para la tabla `preguntas_frecuentes`
--

CREATE TABLE `preguntas_frecuentes` (
  `id_preguntas` bigint(11) NOT NULL,
  `pregunta` text NOT NULL,
  `respuesta` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_frecuentes`
--

INSERT INTO `preguntas_frecuentes` (`id_preguntas`, `pregunta`, `respuesta`) VALUES
(7, '¿Suscripción?', 'El precio de la suscripción cuesta $200/mes.'),
(8, '¿Sobre la mujer?', 'La mujer a sido violentada desde hace mucho, este es un tema my controversial debido a la falta de atención a este sector '),
(10, '¿Cómo contacto con soporte?', 'Puedes escribirnos a soporte@tusitio.com o usar el contactanos.'),
(11, '¿Qué es la violencia contra las mujeres?', 'La violencia contra las mujeres incluye cualquier acto de violencia de género que resulte en daño o sufrimiento físico, sexual, psicológico o económico para una mujer, ya sea en la familia, la comunidad o en el ámbito público.'),
(12, '¿Cómo puedo saber si estoy en una relación abusiva?', 'Una relación abusiva puede incluir control excesivo, insultos, aislamiento, amenazas, violencia física o sexual. Si sientes miedo, humillación o control constante, es importante buscar ayuda.'),
(13, '¿Cómo puedo apoyar a una amiga que está viviendo violencia?', 'Escucha sin juzgar, respeta su ritmo para tomar decisiones, infórmale sobre recursos disponibles y acompáñala a buscar ayuda profesional.'),
(14, '¿La violencia psicológica también es violencia?', 'Sí, la violencia psicológica como insultos, amenazas, manipulación y humillaciones tienen efectos muy graves y son tan dañinas como la violencia física.');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `preguntas_frecuentes`
--
ALTER TABLE `preguntas_frecuentes`
  ADD PRIMARY KEY (`id_preguntas`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `preguntas_frecuentes`
--
ALTER TABLE `preguntas_frecuentes`
  MODIFY `id_preguntas` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
