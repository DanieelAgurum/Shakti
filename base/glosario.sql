-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 20-07-2025 a las 20:10:41
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
-- Estructura de tabla para la tabla `glosario`
--

CREATE TABLE `glosario` (
  `id_glosario` int(11) NOT NULL,
  `icono` text DEFAULT NULL,
  `titulo` text NOT NULL,
  `concepto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `glosario`
--

INSERT INTO `glosario` (`id_glosario`, `icono`, `titulo`, `concepto`) VALUES
(3, '<i class=\"fa-solid fa-house\"></i>', 'Violencia contra la mujer', '<p style=\"text-align: justify;\">Toda acci&oacute;n o conducta, basada en el g&eacute;nero, que cause muerte, da&ntilde;o o sufrimiento f&iacute;sico, sexual o psicol&oacute;gico a una mujer, tanto en el &aacute;mbito p&uacute;blico como en el privado.</p>'),
(4, '<i class=\"fa-solid fa-triangle-exclamation\" style=\"color: #005eff;\"></i>', 'Violencia de género', '<p style=\"text-align: justify;\">Tipo de violencia ejercida contra una persona por raz&oacute;n de su sexo o g&eacute;nero. En el caso de las mujeres, se basa en estructuras patriarcales y relaciones desiguales de poder.</p>'),
(5, '<i class=\"fa-solid fa-circle-exclamation\" style=\"color: #ff0000;\"></i>', 'Feminicidio', '<p style=\"text-align: justify;\">Asesinato de una mujer por el hecho de ser mujer. Es la forma m&aacute;s extrema de violencia de g&eacute;nero, y suele ser resultado de un contexto de impunidad y discriminaci&oacute;n sistem&aacute;tica.</p>'),
(8, '<i class=\"fa-solid fa-exclamation\" style=\"color: #ff0a0a;\"></i>', 'Acoso sexual', '<p style=\"text-align: justify;\">Conductas verbales o f&iacute;sicas de &iacute;ndole sexual que resultan ofensivas, intimidatorias o humillantes para la mujer, realizadas sin su consentimiento. Puede darse en espacios laborales, escolares, p&uacute;blicos o digitales.</p>');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `glosario`
--
ALTER TABLE `glosario`
  ADD PRIMARY KEY (`id_glosario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `glosario`
--
ALTER TABLE `glosario`
  MODIFY `id_glosario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
