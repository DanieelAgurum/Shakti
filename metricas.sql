-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 03-07-2025 a las 21:28:00
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
-- Estructura de tabla para la tabla `metricas`
--

CREATE TABLE `metricas` (
  `id` int(11) NOT NULL,
  `vista` varchar(100) DEFAULT NULL,
  `tiempo_estancia` float DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metricas`
--

INSERT INTO `metricas` (`id`, `vista`, `tiempo_estancia`, `fecha`) VALUES
(28, 'index.php', 93.525, '2025-07-03 00:59:10'),
(29, 'contacto.php', 6.694, '2025-07-03 00:59:17'),
(30, 'alzalaVoz.php', 5.515, '2025-07-03 00:59:23'),
(31, 'publicaciones.php', 5.662, '2025-07-03 00:59:29'),
(32, 'perfil.php', 4.68, '2025-07-03 00:59:33'),
(33, 'index.php', 4.157, '2025-07-03 00:59:38'),
(34, 'login.php', 4.973, '2025-07-03 00:59:43'),
(35, 'registro.php', 3.851, '2025-07-03 00:59:47'),
(36, 'login.php', 102.95, '2025-07-03 01:01:30'),
(37, 'login.php', 3.234, '2025-07-03 12:20:51'),
(38, 'index.php', 61.584, '2025-07-03 13:25:20'),
(39, 'index.php', 4.745, '2025-07-03 13:25:45'),
(40, 'index.php', 2.118, '2025-07-03 13:25:48'),
(41, 'login.php', 0.504, '2025-07-03 13:25:48'),
(42, 'login.php', 0.598, '2025-07-03 13:25:49'),
(43, 'contacto.php', 1.042, '2025-07-03 13:25:50'),
(44, 'index.php', 16.831, '2025-07-03 13:26:07'),
(45, 'login.php', 2.807, '2025-07-03 13:26:10'),
(46, 'perfil.php', 3.333, '2025-07-03 13:26:13'),
(47, 'publicaciones.php', 4.173, '2025-07-03 13:26:17'),
(48, 'alzalaVoz.php', 4.3, '2025-07-03 13:26:22'),
(49, 'contacto.php', 4.408, '2025-07-03 13:26:26'),
(50, 'index.php', 2.971, '2025-07-03 13:26:29'),
(51, 'index.php', 21.715, '2025-07-03 13:26:51'),
(52, 'login.php', 3.498, '2025-07-03 13:26:55');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `metricas`
--
ALTER TABLE `metricas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `metricas`
--
ALTER TABLE `metricas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
