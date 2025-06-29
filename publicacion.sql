-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-06-2025 a las 08:31:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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
-- Estructura de tabla para la tabla `publicacion`
--

CREATE TABLE `publicacion` (
  `id_publicacion` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT current_timestamp(),
  `id_usuarias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicacion`
--

INSERT INTO `publicacion` (`id_publicacion`, `titulo`, `contenido`, `fecha_publicacion`, `id_usuarias`) VALUES
(1, 'hola', 'putos todos', '2025-06-28 03:29:43', 15),
(2, 'hola', 'hi', '2025-06-29 00:31:39', 15),
(3, 'hola', 'hihi', '2025-06-29 00:32:58', 15),
(4, 'hola', 'j', '2025-06-29 00:55:00', 15);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD PRIMARY KEY (`id_publicacion`),
  ADD KEY `fk_id_usuarias` (`id_usuarias`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  MODIFY `id_publicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD CONSTRAINT `fk_id_usuarias` FOREIGN KEY (`id_usuarias`) REFERENCES `usuarias` (`id`),
  ADD CONSTRAINT `fk_publicacion_usuaria` FOREIGN KEY (`id_usuarias`) REFERENCES `usuarias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
