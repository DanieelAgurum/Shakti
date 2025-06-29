-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-06-2025 a las 07:45:13
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
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_06_23_195509_s_h_a_k_t_i', 1);

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
(3, 'hola', 'hihi', '2025-06-29 00:32:58', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'usuaria'),
(2, 'especialista'),
(3, 'administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarias`
--

CREATE TABLE `usuarias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `fecha_nac` date NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `documentos` longblob DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `foto` longblob DEFAULT NULL,
  `estatus` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarias`
--

INSERT INTO `usuarias` (`id`, `nombre`, `apellidos`, `fecha_nac`, `contraseña`, `nickname`, `correo`, `id_rol`, `documentos`, `direccion`, `telefono`, `foto`, `estatus`) VALUES
(15, 'brian ', 'escalante gonzalez', '2003-11-06', '$2y$10$kNIg1hLXwd3756pQ1I3ipeZhmcOmklaHnUhFvF8jGMq8M5uY5mzme', 'brain', 'brianesca28@gmail.com', 1, NULL, NULL, NULL, NULL, 0),
(16, 'Admin', 'General', '1990-01-01', '$2y$10$xvOgIReJXxKJLniCTi32GOU7MSdInWyBeZxSDoiLRoRmg5oufBn..', 'adminuser', 'admin@example.com', 3, NULL, NULL, NULL, NULL, 1),
(17, 'diego', 'perez prado', '2004-01-26', '$2y$10$pyLT99TQ3DG06ap6q.FlL.W35EUZxFG.yXwK8aFisZyk75sV5r/pe', 'bored', 'testin1@example.com', 2, NULL, NULL, NULL, NULL, 0),
(18, 'dan', 'escalante gonzalez', '2004-01-13', '$2y$10$NYZPfwEyPPnGdpJ1cGog8u/2NTSLAz7VWX3Ryfb3F7nBkHRvPB7oK', 'skinny', 'prueba@example.com', 2, NULL, NULL, NULL, NULL, 0),
(19, 'noinfoibefoi', 'niofhfeihfei', '1977-05-28', '$2y$10$hXAofkretV4pgnEP8LtVjOWtagCEXgdnT/OKUvjFq/71GIWUy6wiK', 'flka', 'dddd@ddddd.com', 1, NULL, NULL, NULL, NULL, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD PRIMARY KEY (`id_publicacion`),
  ADD KEY `fk_id_usuarias` (`id_usuarias`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarias`
--
ALTER TABLE `usuarias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nickname` (`nickname`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  MODIFY `id_publicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarias`
--
ALTER TABLE `usuarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD CONSTRAINT `fk_id_usuarias` FOREIGN KEY (`id_usuarias`) REFERENCES `usuarias` (`id`),
  ADD CONSTRAINT `fk_publicacion_usuaria` FOREIGN KEY (`id_usuarias`) REFERENCES `usuarias` (`id`);

--
-- Filtros para la tabla `usuarias`
--
ALTER TABLE `usuarias`
  ADD CONSTRAINT `usuarias_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
