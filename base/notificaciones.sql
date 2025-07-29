-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-07-2025 a las 04:12:18
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
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `id_usuaria_origen` int(11) NOT NULL,
  `id_usuaria_destino` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `leida` tinyint(1) DEFAULT 0,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `id_usuaria_origen`, `id_usuaria_destino`, `mensaje`, `leida`, `fecha_creacion`) VALUES
(1, 46, 23, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:23:03'),
(2, 46, 26, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:23:03'),
(3, 46, 27, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:23:03'),
(4, 46, 40, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:23:03'),
(5, 46, 41, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:23:03'),
(6, 46, 42, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:23:03'),
(7, 46, 43, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:23:03'),
(8, 46, 44, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:23:03'),
(9, 46, 45, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:23:03'),
(10, 46, 25, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:23:03'),
(11, 46, 23, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:30:38'),
(12, 46, 26, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:30:38'),
(13, 46, 27, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:30:38'),
(14, 46, 40, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:30:38'),
(15, 46, 41, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:30:38'),
(16, 46, 42, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:30:38'),
(17, 46, 43, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:30:38'),
(18, 46, 44, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:30:38'),
(19, 46, 45, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:30:38'),
(20, 46, 25, 'Nueva publicación agregada por una usuaria.', 0, '2025-07-26 00:30:38'),
(21, 46, 23, 'Nueva publicación agregada por enrique', 0, '2025-07-26 00:44:50'),
(22, 46, 26, 'Nueva publicación agregada por enrique', 0, '2025-07-26 00:44:50'),
(23, 46, 27, 'Nueva publicación agregada por enrique', 0, '2025-07-26 00:44:50'),
(24, 46, 40, 'Nueva publicación agregada por enrique', 0, '2025-07-26 00:44:50'),
(25, 46, 41, 'Nueva publicación agregada por enrique', 0, '2025-07-26 00:44:50'),
(26, 46, 42, 'Nueva publicación agregada por enrique', 0, '2025-07-26 00:44:50'),
(27, 46, 43, 'Nueva publicación agregada por enrique', 0, '2025-07-26 00:44:50'),
(28, 46, 44, 'Nueva publicación agregada por enrique', 0, '2025-07-26 00:44:50'),
(29, 46, 45, 'Nueva publicación agregada por enrique', 0, '2025-07-26 00:44:50'),
(30, 46, 25, 'Nueva publicación agregada por enrique', 0, '2025-07-26 00:44:50');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_not_origen` (`id_usuaria_origen`),
  ADD KEY `fk_not_destino` (`id_usuaria_destino`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `fk_not_destino` FOREIGN KEY (`id_usuaria_destino`) REFERENCES `usuarias` (`id`),
  ADD CONSTRAINT `fk_not_origen` FOREIGN KEY (`id_usuaria_origen`) REFERENCES `usuarias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
