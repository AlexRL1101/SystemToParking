-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-08-2021 a las 19:57:25
-- Versión del servidor: 10.4.17-MariaDB
-- Versión de PHP: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_hotel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `correo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidopaterno` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidomaterno` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `edad` int(11) NOT NULL,
  `foto` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `correo`, `nombre`, `password`, `apellidopaterno`, `apellidomaterno`, `sexo`, `edad`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'simon.la.pura@gmail.com', 'Panfilo', '$2y$10$nw460DN.PT1XLasU4uY5u.TzSJ5qzOsNMTXcHdW8XYCJ6JK1z4PRC', 'Philips', 'Sanz', 'Man', 21, '', '2021-08-02 18:37:58', '2021-08-02 18:58:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estacionamiento`
--

CREATE TABLE `estacionamiento` (
  `id_estacionamiento` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_reservacion` int(11) NOT NULL,
  `vehiculo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `tipo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numcajon` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `posicion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estacionamiento`
--

INSERT INTO `estacionamiento` (`id_estacionamiento`, `id_cliente`, `id_reservacion`, `vehiculo`, `codigo`, `precio`, `cantidad`, `tipo`, `numcajon`, `estado`, `posicion`, `created_at`, `updated_at`) VALUES
(7, 1, 5, '2016 Nissan', 'MARH24BdaD1d', 65, 1, 'Economy Class', 10, 0, '5_9', '2021-08-02 19:26:50', '2021-08-02 19:26:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `id_reservacion` int(11) NOT NULL,
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id_pago`, `id_reservacion`, `id`, `nombre`, `apellido`, `email`, `customer_id`, `product`, `amount`, `currency`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '', '', '', '', '', '', '', '', '', '2021-08-02 19:00:37', '2021-08-02 19:00:37'),
(2, 2, '', '', '', '', '', '', '', '', '', '2021-08-02 19:13:50', '2021-08-02 19:13:50'),
(3, 3, '', '', '', '', '', '', '', '', '', '2021-08-02 19:19:43', '2021-08-02 19:19:43'),
(4, 4, '', '', '', '', '', '', '', '', '', '2021-08-02 19:23:45', '2021-08-02 19:23:45'),
(5, 5, '', '', '', '', '', '', '', '', '', '2021-08-02 19:25:06', '2021-08-02 19:25:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservacion`
--

CREATE TABLE `reservacion` (
  `id_reservacion` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fechareservar` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fechasalida` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `horareservar` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `horasalida` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `piso` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reservacion`
--

INSERT INTO `reservacion` (`id_reservacion`, `id_cliente`, `fechareservar`, `fechasalida`, `horareservar`, `horasalida`, `piso`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-08-03', '2021-08-05', '15:00', '04:00', '1', '2021-08-02 18:37:59', '2021-08-02 19:00:36'),
(2, 1, '2021-08-11', '2021-09-01', '17:13', '18:13', '1', '2021-08-02 19:00:37', '2021-08-02 19:13:49'),
(3, 1, '2021-08-11', '2021-09-01', '17:13', '18:13', '1', '2021-08-02 19:13:50', '2021-08-02 19:19:43'),
(4, 1, '2021-08-17', '2021-08-20', '18:23', '17:23', '1', '2021-08-02 19:19:43', '2021-08-02 19:23:45'),
(5, 1, '2021-08-10', '2021-08-24', '16:24', '16:25', '1', '2021-08-02 19:23:45', '2021-08-02 19:25:06'),
(6, 1, '', '', '', '', '', '2021-08-02 19:25:06', '2021-08-02 19:25:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transaccion`
--

CREATE TABLE `transaccion` (
  `id_transaccion` int(11) NOT NULL,
  `id_pago` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE `vehiculo` (
  `id_vehiculo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `modelo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marca` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `placa` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seguro` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `propiedad` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vehiculo`
--

INSERT INTO `vehiculo` (`id_vehiculo`, `id_cliente`, `created_at`, `updated_at`, `modelo`, `marca`, `color`, `tipo`, `placa`, `seguro`, `propiedad`, `descripcion`) VALUES
(1, 1, '2021-08-02 18:37:58', '2021-08-02 18:55:52', '2016', 'Nissan', 'Rose', 'Sports-Car', '3HB983', '12323451', 'lwjdnvlnv', 'owVKBIPVBIPUEBRV'),
(2, 1, '2021-08-02 18:55:52', '2021-08-02 18:55:52', '', '', '', '', '', '', '', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `estacionamiento`
--
ALTER TABLE `estacionamiento`
  ADD PRIMARY KEY (`id_estacionamiento`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`);

--
-- Indices de la tabla `reservacion`
--
ALTER TABLE `reservacion`
  ADD PRIMARY KEY (`id_reservacion`);

--
-- Indices de la tabla `transaccion`
--
ALTER TABLE `transaccion`
  ADD PRIMARY KEY (`id_transaccion`);

--
-- Indices de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`id_vehiculo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estacionamiento`
--
ALTER TABLE `estacionamiento`
  MODIFY `id_estacionamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservacion`
--
ALTER TABLE `reservacion`
  MODIFY `id_reservacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `transaccion`
--
ALTER TABLE `transaccion`
  MODIFY `id_transaccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  MODIFY `id_vehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
