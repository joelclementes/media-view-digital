-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 14-03-2026 a las 07:25:22
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
-- Estructura de tabla para la tabla `cat_distritos_ant`
--

DROP TABLE IF EXISTS `cat_distritos_ant`;
CREATE TABLE IF NOT EXISTS `cat_distritos_ant` (
  `idDistrito` int NOT NULL AUTO_INCREMENT,
  `clave` varchar(30) NOT NULL,
  `distrito` varchar(60) NOT NULL,
  PRIMARY KEY (`idDistrito`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `cat_distritos_ant`
--

INSERT INTO `cat_distritos_ant` (`idDistrito`, `clave`, `distrito`) VALUES
(1, 'I', 'Pánuco'),
(2, 'II', 'Tantoyuca'),
(3, 'III', 'Tuxpan'),
(4, 'IV', 'Álamo - Temapache'),
(5, 'V', 'Poza Rica'),
(6, 'VI', 'Papantla'),
(7, 'VII', 'Martínez de la Torre'),
(8, 'VIII', 'Misantla'),
(9, 'IX', 'Perote'),
(10, 'X', 'Xalapa I'),
(11, 'XI', 'Xalapa II'),
(12, 'XII', 'Coatepec'),
(13, 'XIII', 'Emiliano Zapata'),
(14, 'XIV', 'Veracruz I'),
(15, 'XV', 'Veracruz II'),
(16, 'XVI', 'Boca del Río'),
(17, 'XVII', 'Alvarado'),
(18, 'XVIII', 'Huatusco'),
(19, 'XIX', 'Córdoba'),
(20, 'XX', 'Orizaba'),
(21, 'XXI', 'Río Blanco'),
(22, 'XXII', 'Zongolica'),
(23, 'XXIII', 'Cosamaloapan'),
(24, 'XXIV', 'Santiago Tuxtla'),
(25, 'XXV', 'San Andrés Tuxtla'),
(26, 'XXVI', 'Cosoleacaque'),
(27, 'XXVII', 'Acayucan'),
(28, 'XXVIII', 'Minatitlán'),
(29, 'XXIX', 'Coatzacoalcos I'),
(30, 'XXX', 'Coatzacoalcos II');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
