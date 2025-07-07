-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-07-2025 a las 04:41:08
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
-- Estructura de tabla para la tabla `likes_publicaciones`
--

CREATE TABLE `likes_publicaciones` (
  `id_like` int(11) NOT NULL,
  `id_usuaria` int(11) NOT NULL,
  `id_publicacion` int(11) NOT NULL,
  `fecha_like` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `likes_publicaciones`
--

INSERT INTO `likes_publicaciones` (`id_like`, `id_usuaria`, `id_publicacion`, `fecha_like`) VALUES
(1, 28, 17, '2025-07-07 02:20:31'),
(3, 32, 17, '2025-07-07 02:23:56'),
(4, 34, 17, '2025-07-07 02:31:52'),
(7, 34, 19, '2025-07-07 02:32:22'),
(8, 28, 19, '2025-07-07 02:35:29'),
(9, 28, 20, '2025-07-07 02:36:30'),
(10, 34, 20, '2025-07-07 02:37:17');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `likes_publicaciones`
--
ALTER TABLE `likes_publicaciones`
  ADD PRIMARY KEY (`id_like`),
  ADD UNIQUE KEY `id_usuaria` (`id_usuaria`,`id_publicacion`),
  ADD KEY `id_publicacion` (`id_publicacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `likes_publicaciones`
--
ALTER TABLE `likes_publicaciones`
  MODIFY `id_like` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `likes_publicaciones`
--
ALTER TABLE `likes_publicaciones`
  ADD CONSTRAINT `likes_publicaciones_ibfk_1` FOREIGN KEY (`id_usuaria`) REFERENCES `usuarias` (`id`),
  ADD CONSTRAINT `likes_publicaciones_ibfk_2` FOREIGN KEY (`id_publicacion`) REFERENCES `publicacion` (`id_publicacion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
