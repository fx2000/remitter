-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2019 at 03:50 AM
-- Server version: 10.1.43-MariaDB-cll-lve
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `remitter`
--
CREATE DATABASE IF NOT EXISTS `remitter` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `remitter`;

-- --------------------------------------------------------

--
-- Table structure for table `account_investors`
--

CREATE TABLE `account_investors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance` float DEFAULT NULL,
  `tmp_balance` float DEFAULT '0',
  `modify_dt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `api`
--

CREATE TABLE `api` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `api`
--

INSERT INTO `api` (`id`, `name`, `api_key`) VALUES
(1, 'Punto Pago', '32eb5332-80c5-47f1-a9f1-78c02ac233c6');

-- --------------------------------------------------------

--
-- Table structure for table `cpr_accounts`
--

CREATE TABLE `cpr_accounts` (
  `id` int(11) NOT NULL,
  `account_id` varchar(100) NOT NULL,
  `operation_model` int(2) NOT NULL COMMENT '1 => SHARED , 2 => INDIVIDUAL',
  `retailer_id` int(11) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `account_type` int(2) NOT NULL COMMENT '1 => POSTPAID , 2 => PREPAID',
  `credit_limit` varchar(100) NOT NULL,
  `store_id` int(11) NOT NULL,
  `delete_status` int(11) NOT NULL COMMENT '1 -> DELETED , 0  -> ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cpr_banks`
--

CREATE TABLE `cpr_banks` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL COMMENT 'Country',
  `name` varchar(55) NOT NULL COMMENT 'Bank Name',
  `status` int(1) NOT NULL DEFAULT '1',
  `delete_status` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Active, 1 => Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cpr_banks`
--

INSERT INTO `cpr_banks` (`id`, `country_id`, `name`, `status`, `delete_status`) VALUES
(4, 232, 'Banesco', 1, 0),
(5, 232, 'Mercantil', 1, 0),
(6, 232, 'Banco de Venezuela', 1, 0),
(7, 232, 'Banplus', 1, 0),
(8, 232, 'Bancaribe', 1, 0),
(9, 232, 'Banco del Tesoro', 1, 0),
(10, 232, 'Bicentenario Banco Universal', 1, 0),
(11, 232, 'BBVA Provincial', 1, 0),
(12, 232, 'Banco Fondo Común', 1, 0),
(13, 232, 'Banco Occidental de Descuento', 1, 0),
(14, 232, 'Banco Plaza', 1, 0),
(15, 232, 'Banco Exterior', 1, 0),
(16, 170, 'Banco General', 1, 0),
(17, 170, 'Banistmo', 1, 0),
(18, 232, '100% Banco', 1, 0),
(19, 232, 'Banco Agrícola de Venezuela', 1, 0),
(20, 232, 'Banco Activo', 1, 0),
(21, 232, 'Banfanb', 1, 0),
(22, 232, 'Banmujer', 1, 0),
(23, 232, 'Banco Caroní', 1, 0),
(24, 232, 'Casa Propia Entidad de Ahorro y Préstamo', 1, 0),
(25, 232, 'Citibank Venezuela', 1, 0),
(26, 232, 'DELSUR Banco Universal', 1, 0),
(27, 232, 'Mi Casa Entidad de Ahorro y Préstamo', 1, 0),
(28, 232, 'Banco Nacional de Crédito', 1, 0),
(29, 232, 'Banco Sofitasa', 1, 0),
(30, 232, 'Venezolano de Crédito', 1, 0),
(31, 232, '100% Banco', 1, 0),
(32, 232, 'Banco Agrícola de Venezuela', 1, 0),
(33, 232, 'Banco Activo', 1, 0),
(34, 232, 'Banfanb', 1, 0),
(35, 232, 'Banmujer', 1, 0),
(36, 232, 'Banco Caroní', 1, 0),
(37, 232, 'Casa Propia Entidad de Ahorro y Préstamo', 1, 0),
(38, 232, 'Citibank Venezuela', 1, 0),
(39, 232, 'DELSUR Banco Universal', 1, 0),
(40, 232, 'Mi Casa Entidad de Ahorro y Préstamo', 1, 0),
(41, 232, 'Banco Nacional de Crédito', 1, 0),
(42, 232, 'Banco Sofitasa', 1, 0),
(43, 232, 'Venezolano de Crédito', 1, 0),
(44, 170, 'Banco Nacional de Panamá', 1, 0),
(45, 170, 'Multibank', 1, 0),
(46, 170, 'BNP Paribas', 1, 0),
(47, 170, 'BAC International Bank', 1, 0),
(48, 170, 'Global Bank', 1, 0),
(49, 170, 'Caja de Ahorros', 1, 0),
(50, 170, 'Banesco Panamá', 1, 0),
(51, 170, 'Socotiabank Transformandose', 1, 0),
(52, 170, 'Banco Aliado', 1, 0),
(53, 170, 'BLADEX', 1, 0),
(54, 170, 'Banvivienda', 1, 0),
(55, 170, 'Credicorp Bank', 1, 0),
(56, 170, 'Banco Azteca', 1, 0),
(57, 170, 'Canal Bank', 1, 0),
(58, 170, 'St Georges Bank', 1, 0),
(59, 170, 'Primer Banco del Istmo', 1, 0),
(60, 170, 'Towerbank', 1, 0),
(61, 170, 'Banco de Occidente', 1, 0),
(62, 170, 'Banco Pichincha', 1, 0),
(63, 170, 'Banco Davivienda', 1, 0),
(64, 170, 'MMG Bank', 1, 0),
(65, 170, 'Mega International Commercial Bank', 1, 0),
(66, 170, 'Banco Transatlántico', 1, 0),
(67, 170, 'Metrobank', 1, 0),
(68, 170, 'Banco Santander', 1, 0),
(69, 170, 'Mercantil Bank', 1, 0),
(70, 170, 'Banco Lafise', 1, 0),
(71, 170, 'Banco Delta', 1, 0),
(72, 170, 'Banco Panamá', 1, 0),
(73, 170, 'Capital Bank', 1, 0),
(74, 170, 'AllBank', 1, 0),
(75, 170, 'Banco Nacional de Panamá', 1, 0),
(76, 170, 'Multibank', 1, 0),
(77, 170, 'BNP Paribas', 1, 0),
(78, 170, 'BAC International Bank', 1, 0),
(79, 170, 'Global Bank', 1, 0),
(80, 170, 'Caja de Ahorros', 1, 0),
(81, 170, 'Banesco Panamá', 1, 0),
(82, 170, 'Socotiabank Transformandose', 1, 0),
(83, 170, 'Banco Aliado', 1, 0),
(84, 170, 'BLADEX', 1, 0),
(85, 170, 'Banvivienda', 1, 0),
(86, 170, 'Credicorp Bank', 1, 0),
(87, 170, 'Banco Azteca', 1, 0),
(88, 170, 'Canal Bank', 1, 0),
(89, 170, 'St Georges Bank', 1, 0),
(90, 170, 'Primer Banco del Istmo', 1, 0),
(91, 170, 'Towerbank', 1, 0),
(92, 170, 'Banco de Occidente', 1, 0),
(93, 170, 'Banco Pichincha', 1, 0),
(94, 170, 'Banco Davivienda', 1, 0),
(95, 170, 'MMG Bank', 1, 0),
(96, 170, 'Mega International Commercial Bank', 1, 0),
(97, 170, 'Banco Transatlántico', 1, 0),
(98, 170, 'Metrobank', 1, 0),
(99, 170, 'Banco Santander', 1, 0),
(100, 170, 'Mercantil Bank', 1, 0),
(101, 170, 'Banco Lafise', 1, 0),
(102, 170, 'Banco Delta', 1, 0),
(103, 170, 'Banco Panamá', 1, 0),
(104, 170, 'Capital Bank', 1, 0),
(105, 170, 'AllBank', 1, 0),
(106, 232, 'Bangente', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cpr_bank_accounts`
--

CREATE TABLE `cpr_bank_accounts` (
  `id` int(11) NOT NULL,
  `user_id` varchar(45) NOT NULL,
  `bank_id` varchar(45) NOT NULL,
  `account_type` varchar(45) NOT NULL,
  `account_number` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `delete_status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cpr_bank_account_types`
--

CREATE TABLE `cpr_bank_account_types` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cpr_bank_account_types`
--

INSERT INTO `cpr_bank_account_types` (`id`, `name`) VALUES
(1, 'Ahorros'),
(2, 'Corriente');

-- --------------------------------------------------------

--
-- Table structure for table `cpr_cities`
--

CREATE TABLE `cpr_cities` (
  `id` int(11) NOT NULL,
  `city` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cpr_cities`
--

INSERT INTO `cpr_cities` (`id`, `city`) VALUES
(1, 'Panama');

-- --------------------------------------------------------

--
-- Table structure for table `cpr_countries`
--

CREATE TABLE `cpr_countries` (
  `id` int(3) UNSIGNED NOT NULL,
  `code` smallint(6) DEFAULT NULL,
  `iso3166a1` char(2) DEFAULT NULL,
  `iso3166a2` char(3) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cpr_countries`
--

INSERT INTO `cpr_countries` (`id`, `code`, `iso3166a1`, `iso3166a2`, `name`) VALUES
(1, 4, 'AF', 'AFG', 'Afganistán'),
(2, 248, 'AX', 'ALA', 'Islas Gland'),
(3, 8, 'AL', 'ALB', 'Albania'),
(4, 276, 'DE', 'DEU', 'Alemania'),
(5, 20, 'AD', 'AND', 'Andorra'),
(6, 24, 'AO', 'AGO', 'Angola'),
(7, 660, 'AI', 'AIA', 'Anguilla'),
(8, 10, 'AQ', 'ATA', 'Antártida'),
(9, 28, 'AG', 'ATG', 'Antigua y Barbuda'),
(10, 530, 'AN', 'ANT', 'Antillas Holandesas'),
(11, 682, 'SA', 'SAU', 'Arabia Saudí'),
(12, 12, 'DZ', 'DZA', 'Argelia'),
(13, 32, 'AR', 'ARG', 'Argentina'),
(14, 51, 'AM', 'ARM', 'Armenia'),
(15, 533, 'AW', 'ABW', 'Aruba'),
(16, 36, 'AU', 'AUS', 'Australia'),
(17, 40, 'AT', 'AUT', 'Austria'),
(18, 31, 'AZ', 'AZE', 'Azerbaiyán'),
(19, 44, 'BS', 'BHS', 'Bahamas'),
(20, 48, 'BH', 'BHR', 'Bahréin'),
(21, 50, 'BD', 'BGD', 'Bangladesh'),
(22, 52, 'BB', 'BRB', 'Barbados'),
(23, 112, 'BY', 'BLR', 'Bielorrusia'),
(24, 56, 'BE', 'BEL', 'Bélgica'),
(25, 84, 'BZ', 'BLZ', 'Belice'),
(26, 204, 'BJ', 'BEN', 'Benin'),
(27, 60, 'BM', 'BMU', 'Bermudas'),
(28, 64, 'BT', 'BTN', 'Bhután'),
(29, 68, 'BO', 'BOL', 'Bolivia'),
(30, 70, 'BA', 'BIH', 'Bosnia y Herzegovina'),
(31, 72, 'BW', 'BWA', 'Botsuana'),
(32, 74, 'BV', 'BVT', 'Isla Bouvet'),
(33, 76, 'BR', 'BRA', 'Brasil'),
(34, 96, 'BN', 'BRN', 'Brunéi'),
(35, 100, 'BG', 'BGR', 'Bulgaria'),
(36, 854, 'BF', 'BFA', 'Burkina Faso'),
(37, 108, 'BI', 'BDI', 'Burundi'),
(38, 132, 'CV', 'CPV', 'Cabo Verde'),
(39, 136, 'KY', 'CYM', 'Islas Caimán'),
(40, 116, 'KH', 'KHM', 'Camboya'),
(41, 120, 'CM', 'CMR', 'Camerún'),
(42, 124, 'CA', 'CAN', 'Canadá'),
(43, 140, 'CF', 'CAF', 'República Centroafricana'),
(44, 148, 'TD', 'TCD', 'Chad'),
(45, 203, 'CZ', 'CZE', 'República Checa'),
(46, 152, 'CL', 'CHL', 'Chile'),
(47, 156, 'CN', 'CHN', 'China'),
(48, 196, 'CY', 'CYP', 'Chipre'),
(49, 162, 'CX', 'CXR', 'Isla de Navidad'),
(50, 336, 'VA', 'VAT', 'Ciudad del Vaticano'),
(51, 166, 'CC', 'CCK', 'Islas Cocos'),
(52, 170, 'CO', 'COL', 'Colombia'),
(53, 174, 'KM', 'COM', 'Comoras'),
(54, 180, 'CD', 'COD', 'República Democrática del Congo'),
(55, 178, 'CG', 'COG', 'Congo'),
(56, 184, 'CK', 'COK', 'Islas Cook'),
(57, 408, 'KP', 'PRK', 'Corea del Norte'),
(58, 410, 'KR', 'KOR', 'Corea del Sur'),
(59, 384, 'CI', 'CIV', 'Costa de Marfil'),
(60, 188, 'CR', 'CRI', 'Costa Rica'),
(61, 191, 'HR', 'HRV', 'Croacia'),
(62, 192, 'CU', 'CUB', 'Cuba'),
(63, 208, 'DK', 'DNK', 'Dinamarca'),
(64, 212, 'DM', 'DMA', 'Dominica'),
(65, 214, 'DO', 'DOM', 'República Dominicana'),
(66, 218, 'EC', 'ECU', 'Ecuador'),
(67, 818, 'EG', 'EGY', 'Egipto'),
(68, 222, 'SV', 'SLV', 'El Salvador'),
(69, 784, 'AE', 'ARE', 'Emiratos Árabes Unidos'),
(70, 232, 'ER', 'ERI', 'Eritrea'),
(71, 703, 'SK', 'SVK', 'Eslovaquia'),
(72, 705, 'SI', 'SVN', 'Eslovenia'),
(73, 724, 'ES', 'ESP', 'España'),
(74, 581, 'UM', 'UMI', 'Islas ultramarinas de Estados Unidos'),
(75, 840, 'US', 'USA', 'Estados Unidos'),
(76, 233, 'EE', 'EST', 'Estonia'),
(77, 231, 'ET', 'ETH', 'Etiopía'),
(78, 234, 'FO', 'FRO', 'Islas Feroe'),
(79, 608, 'PH', 'PHL', 'Filipinas'),
(80, 246, 'FI', 'FIN', 'Finlandia'),
(81, 242, 'FJ', 'FJI', 'Fiyi'),
(82, 250, 'FR', 'FRA', 'Francia'),
(83, 266, 'GA', 'GAB', 'Gabón'),
(84, 270, 'GM', 'GMB', 'Gambia'),
(85, 268, 'GE', 'GEO', 'Georgia'),
(86, 239, 'GS', 'SGS', 'Islas Georgias del Sur y Sandwich del Sur'),
(87, 288, 'GH', 'GHA', 'Ghana'),
(88, 292, 'GI', 'GIB', 'Gibraltar'),
(89, 308, 'GD', 'GRD', 'Granada'),
(90, 300, 'GR', 'GRC', 'Grecia'),
(91, 304, 'GL', 'GRL', 'Groenlandia'),
(92, 312, 'GP', 'GLP', 'Guadalupe'),
(93, 316, 'GU', 'GUM', 'Guam'),
(94, 320, 'GT', 'GTM', 'Guatemala'),
(95, 254, 'GF', 'GUF', 'Guayana Francesa'),
(96, 324, 'GN', 'GIN', 'Guinea'),
(97, 226, 'GQ', 'GNQ', 'Guinea Ecuatorial'),
(98, 624, 'GW', 'GNB', 'Guinea-Bissau'),
(99, 328, 'GY', 'GUY', 'Guyana'),
(100, 332, 'HT', 'HTI', 'Haití'),
(101, 334, 'HM', 'HMD', 'Islas Heard y McDonald'),
(102, 340, 'HN', 'HND', 'Honduras'),
(103, 344, 'HK', 'HKG', 'Hong Kong'),
(104, 348, 'HU', 'HUN', 'Hungría'),
(105, 356, 'IN', 'IND', 'India'),
(106, 360, 'ID', 'IDN', 'Indonesia'),
(107, 364, 'IR', 'IRN', 'Irán'),
(108, 368, 'IQ', 'IRQ', 'Iraq'),
(109, 372, 'IE', 'IRL', 'Irlanda'),
(110, 352, 'IS', 'ISL', 'Islandia'),
(111, 376, 'IL', 'ISR', 'Israel'),
(112, 380, 'IT', 'ITA', 'Italia'),
(113, 388, 'JM', 'JAM', 'Jamaica'),
(114, 392, 'JP', 'JPN', 'Japón'),
(115, 400, 'JO', 'JOR', 'Jordania'),
(116, 398, 'KZ', 'KAZ', 'Kazajstán'),
(117, 404, 'KE', 'KEN', 'Kenia'),
(118, 417, 'KG', 'KGZ', 'Kirguistán'),
(119, 296, 'KI', 'KIR', 'Kiribati'),
(120, 414, 'KW', 'KWT', 'Kuwait'),
(121, 418, 'LA', 'LAO', 'Laos'),
(122, 426, 'LS', 'LSO', 'Lesotho'),
(123, 428, 'LV', 'LVA', 'Letonia'),
(124, 422, 'LB', 'LBN', 'Líbano'),
(125, 430, 'LR', 'LBR', 'Liberia'),
(126, 434, 'LY', 'LBY', 'Libia'),
(127, 438, 'LI', 'LIE', 'Liechtenstein'),
(128, 440, 'LT', 'LTU', 'Lituania'),
(129, 442, 'LU', 'LUX', 'Luxemburgo'),
(130, 446, 'MO', 'MAC', 'Macao'),
(131, 807, 'MK', 'MKD', 'ARY Macedonia'),
(132, 450, 'MG', 'MDG', 'Madagascar'),
(133, 458, 'MY', 'MYS', 'Malasia'),
(134, 454, 'MW', 'MWI', 'Malawi'),
(135, 462, 'MV', 'MDV', 'Maldivas'),
(136, 466, 'ML', 'MLI', 'Malí'),
(137, 470, 'MT', 'MLT', 'Malta'),
(138, 238, 'FK', 'FLK', 'Islas Malvinas'),
(139, 580, 'MP', 'MNP', 'Islas Marianas del Norte'),
(140, 504, 'MA', 'MAR', 'Marruecos'),
(141, 584, 'MH', 'MHL', 'Islas Marshall'),
(142, 474, 'MQ', 'MTQ', 'Martinica'),
(143, 480, 'MU', 'MUS', 'Mauricio'),
(144, 478, 'MR', 'MRT', 'Mauritania'),
(145, 175, 'YT', 'MYT', 'Mayotte'),
(146, 484, 'MX', 'MEX', 'México'),
(147, 583, 'FM', 'FSM', 'Micronesia'),
(148, 498, 'MD', 'MDA', 'Moldavia'),
(149, 492, 'MC', 'MCO', 'Mónaco'),
(150, 496, 'MN', 'MNG', 'Mongolia'),
(151, 500, 'MS', 'MSR', 'Montserrat'),
(152, 508, 'MZ', 'MOZ', 'Mozambique'),
(153, 104, 'MM', 'MMR', 'Myanmar'),
(154, 516, 'NA', 'NAM', 'Namibia'),
(155, 520, 'NR', 'NRU', 'Nauru'),
(156, 524, 'NP', 'NPL', 'Nepal'),
(157, 558, 'NI', 'NIC', 'Nicaragua'),
(158, 562, 'NE', 'NER', 'Níger'),
(159, 566, 'NG', 'NGA', 'Nigeria'),
(160, 570, 'NU', 'NIU', 'Niue'),
(161, 574, 'NF', 'NFK', 'Isla Norfolk'),
(162, 578, 'NO', 'NOR', 'Noruega'),
(163, 540, 'NC', 'NCL', 'Nueva Caledonia'),
(164, 554, 'NZ', 'NZL', 'Nueva Zelanda'),
(165, 512, 'OM', 'OMN', 'Omán'),
(166, 528, 'NL', 'NLD', 'Países Bajos'),
(167, 586, 'PK', 'PAK', 'Pakistán'),
(168, 585, 'PW', 'PLW', 'Palau'),
(169, 275, 'PS', 'PSE', 'Palestina'),
(170, 591, 'PA', 'PAN', 'Panamá'),
(171, 598, 'PG', 'PNG', 'Papúa Nueva Guinea'),
(172, 600, 'PY', 'PRY', 'Paraguay'),
(173, 604, 'PE', 'PER', 'Perú'),
(174, 612, 'PN', 'PCN', 'Islas Pitcairn'),
(175, 258, 'PF', 'PYF', 'Polinesia Francesa'),
(176, 616, 'PL', 'POL', 'Polonia'),
(177, 620, 'PT', 'PRT', 'Portugal'),
(178, 630, 'PR', 'PRI', 'Puerto Rico'),
(179, 634, 'QA', 'QAT', 'Qatar'),
(180, 826, 'GB', 'GBR', 'Reino Unido'),
(181, 638, 'RE', 'REU', 'Reunión'),
(182, 646, 'RW', 'RWA', 'Ruanda'),
(183, 642, 'RO', 'ROU', 'Rumania'),
(184, 643, 'RU', 'RUS', 'Rusia'),
(185, 732, 'EH', 'ESH', 'Sahara Occidental'),
(186, 90, 'SB', 'SLB', 'Islas Salomón'),
(187, 882, 'WS', 'WSM', 'Samoa'),
(188, 16, 'AS', 'ASM', 'Samoa Americana'),
(189, 659, 'KN', 'KNA', 'San Cristóbal y Nevis'),
(190, 674, 'SM', 'SMR', 'San Marino'),
(191, 666, 'PM', 'SPM', 'San Pedro y Miquelón'),
(192, 670, 'VC', 'VCT', 'San Vicente y las Granadinas'),
(193, 654, 'SH', 'SHN', 'Santa Helena'),
(194, 662, 'LC', 'LCA', 'Santa Lucía'),
(195, 678, 'ST', 'STP', 'Santo Tomé y Príncipe'),
(196, 686, 'SN', 'SEN', 'Senegal'),
(197, 891, 'CS', 'SCG', 'Serbia y Montenegro'),
(198, 690, 'SC', 'SYC', 'Seychelles'),
(199, 694, 'SL', 'SLE', 'Sierra Leona'),
(200, 702, 'SG', 'SGP', 'Singapur'),
(201, 760, 'SY', 'SYR', 'Siria'),
(202, 706, 'SO', 'SOM', 'Somalia'),
(203, 144, 'LK', 'LKA', 'Sri Lanka'),
(204, 748, 'SZ', 'SWZ', 'Suazilandia'),
(205, 710, 'ZA', 'ZAF', 'Sudáfrica'),
(206, 736, 'SD', 'SDN', 'Sudán'),
(207, 752, 'SE', 'SWE', 'Suecia'),
(208, 756, 'CH', 'CHE', 'Suiza'),
(209, 740, 'SR', 'SUR', 'Surinam'),
(210, 744, 'SJ', 'SJM', 'Svalbard y Jan Mayen'),
(211, 764, 'TH', 'THA', 'Tailandia'),
(212, 158, 'TW', 'TWN', 'Taiwán'),
(213, 834, 'TZ', 'TZA', 'Tanzania'),
(214, 762, 'TJ', 'TJK', 'Tayikistán'),
(215, 86, 'IO', 'IOT', 'Territorio Británico del Océano Índico'),
(216, 260, 'TF', 'ATF', 'Territorios Australes Franceses'),
(217, 626, 'TL', 'TLS', 'Timor Oriental'),
(218, 768, 'TG', 'TGO', 'Togo'),
(219, 772, 'TK', 'TKL', 'Tokelau'),
(220, 776, 'TO', 'TON', 'Tonga'),
(221, 780, 'TT', 'TTO', 'Trinidad y Tobago'),
(222, 788, 'TN', 'TUN', 'Túnez'),
(223, 796, 'TC', 'TCA', 'Islas Turcas y Caicos'),
(224, 795, 'TM', 'TKM', 'Turkmenistán'),
(225, 792, 'TR', 'TUR', 'Turquía'),
(226, 798, 'TV', 'TUV', 'Tuvalu'),
(227, 804, 'UA', 'UKR', 'Ucrania'),
(228, 800, 'UG', 'UGA', 'Uganda'),
(229, 858, 'UY', 'URY', 'Uruguay'),
(230, 860, 'UZ', 'UZB', 'Uzbekistán'),
(231, 548, 'VU', 'VUT', 'Vanuatu'),
(232, 862, 'VE', 'VEN', 'Venezuela'),
(233, 704, 'VN', 'VNM', 'Vietnam'),
(234, 92, 'VG', 'VGB', 'Islas Vírgenes Británicas'),
(235, 850, 'VI', 'VIR', 'Islas Vírgenes de los Estados Unidos'),
(236, 876, 'WF', 'WLF', 'Wallis y Futuna'),
(237, 887, 'YE', 'YEM', 'Yemen'),
(238, 262, 'DJ', 'DJI', 'Yibuti'),
(239, 894, 'ZM', 'ZMB', 'Zambia'),
(240, 716, 'ZW', 'ZWE', 'Zimbabue');

-- --------------------------------------------------------

--
-- Table structure for table `cpr_payments`
--

CREATE TABLE `cpr_payments` (
  `id` int(11) NOT NULL,
  `trans_dt` datetime NOT NULL,
  `investor_id` int(11) NOT NULL,
  `titular` varchar(255) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `bank_account_number` varchar(25) DEFAULT NULL,
  `bank_account_type` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `amount` float DEFAULT NULL,
  `fee` float DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `descriptions` varchar(200) DEFAULT NULL,
  `delete_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cpr_provinces`
--

CREATE TABLE `cpr_provinces` (
  `id` int(11) NOT NULL,
  `provinces` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cpr_provinces`
--

INSERT INTO `cpr_provinces` (`id`, `provinces`) VALUES
(1, 'Panama');

-- --------------------------------------------------------

--
-- Table structure for table `cpr_recipients`
--

CREATE TABLE `cpr_recipients` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `fname1` varchar(45) NOT NULL,
  `fname2` varchar(45) DEFAULT NULL,
  `lname1` varchar(45) NOT NULL,
  `lname2` varchar(45) DEFAULT NULL,
  `tax_id` varchar(45) DEFAULT NULL,
  `passport` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `bank_account_type` int(11) NOT NULL,
  `bank_account_number` varchar(45) NOT NULL,
  `status` tinyint(11) NOT NULL,
  `delete_status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cpr_settings`
--

CREATE TABLE `cpr_settings` (
  `id` int(11) NOT NULL,
  `country_id` int(3) NOT NULL,
  `tax` float NOT NULL,
  `sale_rate` float NOT NULL,
  `fee` float NOT NULL,
  `fee2` float NOT NULL COMMENT 'PP Fees',
  `status` int(1) NOT NULL,
  `service_status` int(1) NOT NULL,
  `delete_status` int(1) NOT NULL,
  `cpr_settingscol` varchar(45) DEFAULT NULL,
  `purchase_rate` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cpr_settings`
--

INSERT INTO `cpr_settings` (`id`, `country_id`, `tax`, `sale_rate`, `fee`, `fee2`, `status`, `service_status`, `delete_status`, `cpr_settingscol`, `purchase_rate`) VALUES
(1, 170, 0, 36000, 0, 2, 1, 1, 0, NULL, 33500);

-- --------------------------------------------------------

--
-- Table structure for table `cpr_slideshows`
--

CREATE TABLE `cpr_slideshows` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `time` varchar(10) NOT NULL COMMENT 'In minutes',
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_status` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL COMMENT '0 => INACTIVE , 1 => ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cpr_smtp_settings`
--

CREATE TABLE `cpr_smtp_settings` (
  `id` int(11) NOT NULL,
  `server_name` varchar(50) NOT NULL,
  `port` varchar(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cpr_user_types`
--

CREATE TABLE `cpr_user_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cpr_user_types`
--

INSERT INTO `cpr_user_types` (`id`, `name`) VALUES
(1, 'Administrador'),
(2, 'Supervisor'),
(3, 'Operador'),
(4, 'Invesionista'),
(5, 'Cliente'),
(6, 'Tesorero'),
(7, 'Atención al Cliente');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `id_remesa` int(11) DEFAULT NULL,
  `nombre` varchar(80) NOT NULL,
  `cedula` varchar(15) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `descuento` float NOT NULL DEFAULT '0',
  `total_pagos` float NOT NULL,
  `total_final` float NOT NULL,
  `recargos` float NOT NULL DEFAULT '0',
  `porcentaje_recargo` float NOT NULL DEFAULT '0',
  `efectivo` float NOT NULL DEFAULT '0',
  `cheque` float NOT NULL DEFAULT '0',
  `tarjeta_credito` float NOT NULL DEFAULT '0',
  `tarjeta_debito` float NOT NULL DEFAULT '0',
  `nota_credito` float NOT NULL DEFAULT '0',
  `otro_pago` float NOT NULL DEFAULT '0',
  `dv` varchar(2) DEFAULT NULL,
  `codigo` varchar(25) NOT NULL,
  `nombre_articulo` varchar(80) NOT NULL,
  `unidad` varchar(20) NOT NULL,
  `cantidad` int(7) NOT NULL DEFAULT '1',
  `precio_neto` float NOT NULL,
  `alicuota` float NOT NULL,
  `agrupado` int(2) NOT NULL DEFAULT '2',
  `isc` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `remittances`
--

CREATE TABLE `remittances` (
  `id` int(11) NOT NULL COMMENT 'ID Transacción',
  `trans_dt` datetime NOT NULL COMMENT 'Fecha & Hora de Remesa',
  `reserved_dt` datetime DEFAULT NULL COMMENT 'Fecha & Hora de Reserva',
  `applyed_dt` datetime DEFAULT NULL COMMENT 'Fecha & Hora de Reporte',
  `delivered_dt` datetime DEFAULT NULL COMMENT 'Fecha & Hora de Verificacion',
  `client_id` int(11) NOT NULL COMMENT 'ID Cliente',
  `recipient_id` int(11) NOT NULL COMMENT 'ID Beneficiario',
  `investor_id` int(11) DEFAULT NULL COMMENT 'ID Inversionista',
  `amount` double NOT NULL COMMENT 'Monto de la Remesa',
  `trans_charge` float DEFAULT NULL COMMENT 'Tarifa',
  `tax` float DEFAULT NULL COMMENT 'ITBMS',
  `fee` varchar(45) DEFAULT NULL COMMENT 'Recargo por Transacción',
  `payment_type` int(11) DEFAULT NULL COMMENT 'Método de Pago 1=Efectivo 2=Transferencia 3=PuntoPago',
  `purchase_rate` float DEFAULT NULL COMMENT 'Tasa de Compra',
  `sale_rate` float DEFAULT NULL COMMENT 'Tasa de Venta',
  `amount_payed` float DEFAULT NULL COMMENT 'Monto Total Pagado',
  `amount_sold` float DEFAULT NULL COMMENT 'Monto Vendido a Inversionista',
  `amount_delivered` float DEFAULT NULL COMMENT 'Monto Entregado en Bolívares',
  `status` int(11) NOT NULL COMMENT 'Estado 1=Disponible 2=Reservado 3=En Verificación 4=Completado 5=Cancelado',
  `operator_id` int(11) NOT NULL COMMENT 'Cajero',
  `device_id` varchar(55) NOT NULL DEFAULT '0',
  `delete_status` int(11) NOT NULL DEFAULT '0' COMMENT 'Eliminado',
  `descriptions` varchar(200) DEFAULT NULL COMMENT 'Número de Transferencia Bancaria',
  `photo` varchar(255) DEFAULT NULL COMMENT 'Documento',
  `photo_dir` varchar(255) DEFAULT NULL COMMENT 'Directorio Documento',
  `ach` varchar(255) DEFAULT NULL COMMENT 'Comprobante ACH',
  `ach_dir` varchar(255) DEFAULT NULL COMMENT 'Directorio Comprobante ACH'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type` int(11) NOT NULL DEFAULT '5' COMMENT '1 => Admin , 2 => Supervisor, 3 => Operador , 4 => Inversionista, 5 => Cliente',
  `assigned_to` int(11) DEFAULT NULL,
  `fname1` varchar(255) NOT NULL,
  `fname2` varchar(255) DEFAULT '',
  `lname1` varchar(255) DEFAULT '',
  `lname2` varchar(255) DEFAULT '',
  `email` varchar(255) NOT NULL,
  `password` varchar(50) DEFAULT NULL,
  `pin` int(4) UNSIGNED ZEROFILL NOT NULL DEFAULT '1234',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 => INACTIVE , 1 => ACTIVE',
  `username` varchar(150) DEFAULT NULL,
  `delete_status` int(1) NOT NULL DEFAULT '0' COMMENT '1 -> DELETED , 0  -> ACTIVE',
  `login_status` tinyint(1) DEFAULT NULL COMMENT '0 => N , 1 => Y',
  `uid` varchar(50) DEFAULT NULL,
  `tax_id` varchar(11) DEFAULT NULL,
  `passport` varchar(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `born_country` int(3) DEFAULT NULL,
  `gender` int(1) DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `neighborhood` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` int(3) DEFAULT NULL,
  `home_phone` varchar(16) DEFAULT NULL,
  `mobile_phone` varchar(16) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `photo_dir` varchar(255) DEFAULT NULL,
  `register_dt` datetime DEFAULT NULL,
  `modified_dt` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `assigned_to`, `fname1`, `fname2`, `lname1`, `lname2`, `email`, `password`, `pin`, `status`, `username`, `delete_status`, `login_status`, `uid`, `tax_id`, `passport`, `birthday`, `born_country`, `gender`, `profession`, `address`, `neighborhood`, `town`, `district`, `state`, `country`, `home_phone`, `mobile_phone`, `photo`, `photo_dir`, `register_dt`, `modified_dt`) VALUES
(1, 1, NULL, 'Administrador', '', '', '', 'admin@remitter.appstic.net', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2372, 1, 'admin', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-10-04 13:31:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_investors`
--
ALTER TABLE `account_investors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api`
--
ALTER TABLE `api`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_accounts`
--
ALTER TABLE `cpr_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_banks`
--
ALTER TABLE `cpr_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_bank_accounts`
--
ALTER TABLE `cpr_bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_bank_account_types`
--
ALTER TABLE `cpr_bank_account_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_cities`
--
ALTER TABLE `cpr_cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_countries`
--
ALTER TABLE `cpr_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_payments`
--
ALTER TABLE `cpr_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_provinces`
--
ALTER TABLE `cpr_provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_recipients`
--
ALTER TABLE `cpr_recipients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_settings`
--
ALTER TABLE `cpr_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_slideshows`
--
ALTER TABLE `cpr_slideshows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_smtp_settings`
--
ALTER TABLE `cpr_smtp_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpr_user_types`
--
ALTER TABLE `cpr_user_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remittances`
--
ALTER TABLE `remittances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_type` (`user_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_investors`
--
ALTER TABLE `account_investors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api`
--
ALTER TABLE `api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cpr_accounts`
--
ALTER TABLE `cpr_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cpr_banks`
--
ALTER TABLE `cpr_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `cpr_bank_accounts`
--
ALTER TABLE `cpr_bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cpr_bank_account_types`
--
ALTER TABLE `cpr_bank_account_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cpr_cities`
--
ALTER TABLE `cpr_cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cpr_countries`
--
ALTER TABLE `cpr_countries`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `cpr_payments`
--
ALTER TABLE `cpr_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cpr_provinces`
--
ALTER TABLE `cpr_provinces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cpr_recipients`
--
ALTER TABLE `cpr_recipients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cpr_settings`
--
ALTER TABLE `cpr_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cpr_slideshows`
--
ALTER TABLE `cpr_slideshows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cpr_smtp_settings`
--
ALTER TABLE `cpr_smtp_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cpr_user_types`
--
ALTER TABLE `cpr_user_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `remittances`
--
ALTER TABLE `remittances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Transacción';

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
