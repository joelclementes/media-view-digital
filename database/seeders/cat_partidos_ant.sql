-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 14-03-2026 a las 07:43:38
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_akeimediaview`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_partidos_ant`
--

DROP TABLE IF EXISTS `cat_partidos_ant`;
CREATE TABLE IF NOT EXISTS `cat_partidos_ant` (
  `idPartido` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `siglas` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `tipo` varchar(35) NOT NULL,
  PRIMARY KEY (`idPartido`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `cat_partidos_ant`
--

INSERT INTO `cat_partidos_ant` (`idPartido`, `nombre`, `siglas`, `logo`, `tipo`) VALUES
(1, 'Partido Acción Nacional', 'PAN', 'PAN.png', 'Partido'),
(2, 'Partido Revolucionario Institucional', 'PRI', 'PRI.png', 'Partido'),
(3, 'Partido del Trabajo', 'PT', 'PT.png', 'Partido'),
(4, 'Partido Verde Ecologista de México', 'PVEM', 'PVEM.png', 'Partido'),
(5, 'Movimiento Ciudadano', 'MC', 'MOV_C.png', 'Partido'),
(6, 'Morena', 'MORENA', 'MORENA.png', 'Partido'),
(7, 'Democraticos Unidos por Veracruz', 'DEMOCRÁTICOS UNIDOS POR VERACRUZ', 'Democráticos.png', 'Asociación'),
(8, 'Unidad y Democracia', 'UNIDAD Y DEMOCRACIA', 'Unidad.png', 'Asociación'),
(9, 'Via Veracruzana', 'VÍA VERACRUZANA', 'Vía.png', 'Asociación'),
(10, 'Fuerza Veracruzana', 'FUERZA VERACRUZANA', 'Fuerza.png', 'Asociación'),
(11, 'Generando Bienestar 3', 'GENERANDO BIENESTAR 3', 'Generando.png', 'Asociación'),
(12, 'Ganemos México la Confianza', 'GANEMOS MÉXICO LA CONFIANZA', 'Ganemos.png', 'Asociación'),
(13, 'Unión Veracruzana por la Evolución de la Sociedad', 'UNIÓN VERACRUZANA POR LA EVOLUCIÓN DE LA SOCIEDAD', 'Unión.png', 'Asociación'),
(14, 'Democracia e Igualdad Veracruzana', 'DEMOCRACIA E IGUALDAD VERACRUZANA', 'Democracia.png', 'Asociación'),
(15, 'Alianza Generacional', 'ALIANZA GENERACIONAL', 'Alianza-Generacional.png', 'Asociación'),
(16, 'Expresión Ciudadana de Veracruz', 'EXPRESIÓN CIUDADANA DE VERACRUZ', 'partido2.png', 'Asociación'),
(17, 'Participación Veracruzana', 'PARTICIPACIÓN VERACRUZANA', 'participacion_veracruzana.png', 'Asociación'),
(18, 'Compromiso con Veracruz', 'COMPROMISO CON VERACRUZ', 'compromiso_veracruz.png', 'Asociación'),
(19, 'Esperanza Veracruzana', 'ESPERANZA VERACRUZANA', 'esperanza.png', 'Asociación'),
(20, 'Equipo Político ', 'EQUIPO POLÍTICO', 'epv.jpg', 'Asociación'),
(21, 'Cardenista', 'CARDENISTA', '', 'Asociación'),
(22, 'Independiente', 'Candidaturas Independientes', '', 'Partido'),
(23, 'Sigamos Haciendo Historia en Veracruz', 'Sigamos Haciendo Historia en Veracruz', '', 'Partido'),
(24, 'N/A', 'N/A', '', 'N/A');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
