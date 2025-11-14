-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 13-11-2025 a las 18:06:59
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
-- Base de datos: `TiendaPlus`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_agregado` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id`, `usuario_id`, `producto_id`, `cantidad`, `fecha_agregado`) VALUES
(10, 2, 1, 1, '2025-10-09 13:50:37'),
(14, 17, 3, 6, '2025-10-09 17:00:30'),
(22, 2, 2, 6, '2025-10-09 20:14:00'),
(24, 22, 2, 1, '2025-10-10 14:28:05'),
(25, 22, 2, 2, '2025-10-10 14:54:31'),
(26, 22, 2, 3, '2025-10-10 15:37:45'),
(27, 22, 2, 1, '2025-10-10 15:38:27'),
(28, 22, 1, 1, '2025-10-10 16:05:05'),
(38, 48, 3, 1, '2025-10-10 21:13:52'),
(65, 48, 7, 1, '2025-10-11 16:04:26'),
(66, 48, 7, 1, '2025-10-11 16:05:04'),
(75, 57, 7, 1, '2025-10-09 13:50:28'),
(80, 59, 2, 2, '2025-10-13 11:02:12'),
(100, 17, 3, 1, '2025-10-15 14:30:44'),
(186, 23, 1, 1, '2025-10-16 17:15:07'),
(187, 23, 1, 1, '2025-10-16 17:15:25'),
(188, 23, 1, 1, '2025-10-16 17:24:33'),
(189, 23, 7, 2, '2025-10-16 17:24:49'),
(190, 23, 2, 1, '2025-10-16 17:31:44'),
(191, 23, 2, 1, '2025-10-17 11:16:06'),
(192, 23, 6, 1, '2025-10-17 11:18:05'),
(193, 23, 7, 2, '2025-10-17 15:19:23'),
(194, 23, 7, 2, '2025-10-17 15:19:35'),
(195, 23, 6, 1, '2025-10-17 15:19:38'),
(196, 23, 7, 1, '2025-10-24 12:20:27'),
(289, 49, 6, 1, '2025-10-27 16:57:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `fecha_creacion`) VALUES
(2, 'Vestidos verde', 'Categoría de vestidos', '2025-09-23 22:06:03'),
(3, 'Zapatos', 'Categoría de zapatos', '2025-09-23 22:06:03'),
(4, 'Accesorios', 'Categoría de accesorios', '2025-09-23 22:06:03'),
(5, 'Ofertas', 'Productos en oferta', '2025-09-23 22:06:03'),
(6, 'blusa', 'roja', '2025-10-06 17:04:36'),
(10, 'Vestidos rosa', 'Categoría de vestidos', '2025-09-23 22:06:03'),
(11, 'Vestidos rosa', 'Categoría de vestidos', '2025-09-23 22:06:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio`) VALUES
(9, 3, 3, 0, 0.00),
(11, 9, 6, 3, 0.00),
(33, 31, 2, 2, 0.00),
(34, 32, 3, 1, 0.00),
(35, 33, 2, 1, 0.00),
(36, 34, 2, 1, 0.00),
(37, 35, 2, 2, 0.00),
(38, 36, 3, 1, 0.00),
(39, 37, 3, 1, 0.00),
(40, 38, 2, 1, 0.00),
(41, 39, 7, 1, 0.00),
(42, 40, 2, 1, 0.00),
(43, 41, 8, 2, 0.00),
(44, 42, 2, 2, 0.00),
(45, 43, 2, 1, 0.00),
(46, 44, 3, 1, 0.00),
(47, 45, 2, 2, 0.00),
(48, 46, 3, 1, 0.00),
(49, 47, 2, 1, 0.00),
(50, 47, 1, 1, 0.00),
(51, 48, 1, 1, 0.00),
(52, 49, 1, 1, 0.00),
(53, 50, 3, 1, 0.00),
(54, 50, 7, 1, 0.00),
(55, 51, 3, 1, 0.00),
(56, 51, 7, 1, 0.00),
(57, 52, 1, 1, 0.00),
(58, 52, 7, 1, 0.00),
(59, 53, 7, 1, 0.00),
(60, 53, 2, 1, 0.00),
(61, 54, 7, 1, 0.00),
(62, 54, 2, 1, 0.00),
(63, 55, 7, 1, 0.00),
(64, 55, 1, 1, 0.00),
(65, 56, 7, 1, 0.00),
(66, 56, 1, 1, 0.00),
(67, 56, 2, 1, 0.00),
(68, 57, 7, 1, 0.00),
(69, 57, 2, 1, 0.00),
(70, 58, 7, 1, 0.00),
(71, 58, 2, 1, 0.00),
(72, 59, 2, 3, 0.00),
(75, 65, 3, 1, 0.00),
(76, 66, 2, 2, 0.00),
(77, 68, 3, 1, 0.00),
(78, 68, 2, 1, 0.00),
(79, 69, 2, 1, 0.00),
(80, 80, 3, 3, 189000.00),
(81, 80, 1, 1, 75000.00),
(82, 81, 3, 3, 189000.00),
(83, 81, 1, 1, 75000.00),
(84, 82, 3, 3, 189000.00),
(85, 82, 1, 1, 75000.00),
(86, 83, 3, 3, 189000.00),
(87, 83, 1, 1, 75000.00),
(88, 190, 2, 2, 120000.00),
(89, 190, 1, 1, 75000.00),
(90, 191, 2, 1, 120000.00),
(91, 192, 1, 1, 75000.00),
(92, 193, 2, 1, 120000.00),
(93, 194, 3, 2, 189000.00),
(94, 194, 6, 1, 85000.00),
(95, 195, 2, 1, 120000.00),
(96, 195, 6, 1, 85000.00),
(97, 195, 7, 1, 95000.00),
(98, 196, 3, 2, 189000.00),
(99, 196, 6, 1, 85000.00),
(100, 197, 3, 3, 189000.00),
(101, 198, 2, 1, 120000.00),
(102, 199, 2, 1, 120000.00),
(103, 200, 3, 1, 189000.00),
(104, 201, 2, 1, 120000.00),
(105, 202, 3, 1, 189000.00),
(106, 203, 2, 1, 120000.00),
(107, 203, 3, 1, 189000.00),
(108, 204, 2, 3, 120000.00),
(109, 205, 2, 1, 120000.00),
(110, 206, 1, 1, 75000.00),
(111, 207, 3, 1, 189000.00),
(112, 208, 1, 2, 75000.00),
(113, 208, 2, 1, 120000.00),
(114, 209, 2, 1, 120000.00),
(115, 210, 3, 1, 189000.00),
(116, 211, 2, 1, 120000.00),
(117, 213, 4, 1, 189000.00),
(118, 213, 1, 1, 75000.00),
(119, 214, 1, 1, 75000.00),
(120, 215, 2, 1, 120000.00),
(121, 216, 2, 1, 120000.00),
(122, 217, 2, 1, 120000.00),
(123, 218, 2, 1, 120000.00),
(124, 219, 2, 1, 120000.00),
(125, 219, 1, 1, 75000.00),
(126, 220, 2, 1, 120000.00),
(127, 221, 2, 1, 120000.00),
(128, 222, 3, 1, 189000.00),
(129, 223, 2, 1, 120000.00),
(130, 224, 2, 1, 120000.00),
(131, 225, 2, 1, 120000.00),
(132, 226, 2, 1, 120000.00),
(133, 227, 3, 1, 189000.00),
(134, 228, 2, 2, 120000.00),
(135, 229, 3, 1, 189000.00),
(136, 230, 2, 3, 120000.00),
(137, 231, 2, 1, 120000.00),
(138, 232, 3, 1, 189000.00),
(139, 233, 2, 3, 120000.00),
(140, 234, 2, 1, 120000.00),
(141, 235, 2, 1, 120000.00),
(142, 236, 1, 1, 75000.00),
(143, 237, 3, 1, 189000.00),
(144, 238, 4, 3, 189000.00),
(145, 238, 2, 2, 120000.00),
(146, 238, 1, 1, 75000.00),
(147, 239, 2, 1, 120000.00),
(148, 240, 4, 1, 189000.00),
(149, 241, 2, 1, 120000.00),
(153, 245, 2, 1, 120000.00),
(154, 246, 1, 2, 75000.00),
(155, 247, 1, 3, 75000.00),
(156, 247, 4, 1, 189000.00),
(157, 248, 1, 1, 75000.00),
(158, 249, 1, 1, 75000.00),
(159, 250, 2, 1, 120000.00),
(160, 251, 1, 1, 75000.00),
(161, 252, 1, 1, 75000.00),
(162, 253, 6, 3, 85000.00),
(163, 254, 6, 1, 85000.00),
(164, 254, 4, 1, 189000.00),
(165, 255, 2, 1, 120000.00),
(166, 255, 1, 1, 75000.00),
(167, 256, 2, 1, 120000.00),
(168, 256, 1, 2, 75000.00),
(169, 257, 1, 3, 75000.00),
(170, 258, 1, 1, 75000.00),
(171, 259, 1, 1, 75000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','procesando','completado','cancelado') DEFAULT 'pendiente',
  `cliente` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ciudad` varchar(100) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `fecha`, `total`, `estado`, `cliente`, `email`, `direccion`, `ciudad`, `metodo_pago`) VALUES
(3, 4, '2025-10-08 03:58:50', 150000.00, 'pendiente', 'Bleidis Cervantes', '', NULL, '', ''),
(4, 4, '2025-10-08 04:28:01', 150000.00, 'pendiente', '', '', NULL, '', ''),
(5, 4, '2025-10-08 04:34:34', 309000.00, 'pendiente', '', '', NULL, '', ''),
(6, 4, '2025-10-08 04:35:46', 479000.00, 'pendiente', '', '', NULL, '', ''),
(7, 4, '2025-10-08 04:38:56', 599000.00, 'pendiente', '', '', NULL, '', ''),
(8, 4, '2025-10-08 04:39:24', 599000.00, 'pendiente', '', '', NULL, '', ''),
(9, 2, '2025-10-08 03:48:49', 450000.00, 'pendiente', '', '', NULL, '', ''),
(10, 26, '2025-10-08 03:48:49', 450000.00, 'pendiente', '', '', NULL, '', ''),
(11, 36, '2025-10-09 19:21:05', 750000.00, 'pendiente', '', '', NULL, '', ''),
(12, 5, '2025-09-25 23:52:46', 99000.00, 'pendiente', '', '', NULL, '', ''),
(31, 4, '2025-10-11 03:12:44', 240000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', NULL, '', ''),
(32, 4, '2025-10-11 03:13:11', 189000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', NULL, '', ''),
(33, 4, '2025-10-11 03:18:31', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', NULL, '', ''),
(34, 4, '2025-10-11 03:20:35', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', NULL, '', ''),
(35, 4, '2025-10-11 15:50:56', 240000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', NULL, '', ''),
(36, 49, '2025-10-11 16:58:51', 189000.00, 'pendiente', 'Administrador ', 'admin@tiendaplus.com', NULL, '', ''),
(37, 49, '2025-10-11 16:59:03', 189000.00, 'pendiente', 'Administrador ', 'admin@tiendaplus.com', NULL, '', ''),
(38, 49, '2025-10-11 16:59:52', 120000.00, 'pendiente', 'Administrador ', 'admin@tiendaplus.com', NULL, '', ''),
(39, 49, '2025-10-11 17:01:47', 95000.00, 'pendiente', 'Administrador ', 'admin@tiendaplus.com', NULL, '', ''),
(40, 49, '2025-10-11 17:03:49', 120000.00, 'pendiente', 'Administrador ', 'admin@tiendaplus.com', NULL, '', ''),
(41, 49, '2025-10-11 17:09:08', 170000.00, 'pendiente', 'Administrador ', 'admin@tiendaplus.com', NULL, '', ''),
(42, 4, '2025-10-11 17:13:20', 240000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', NULL, '', ''),
(43, 4, '2025-10-11 20:18:10', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', NULL, '', ''),
(44, 4, '2025-10-11 20:38:44', 189000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', NULL, '', ''),
(45, 4, '2025-10-11 20:48:47', 240000.00, 'pendiente', 'Bleidis Cervantes', '', NULL, '', ''),
(46, 4, '2025-10-11 20:48:58', 189000.00, 'pendiente', 'Bleidis Cervantes', '', NULL, '', ''),
(47, 4, '2025-10-11 20:56:34', 195000.00, 'pendiente', 'Bleidis Cervantes', '', NULL, '', ''),
(48, 4, '2025-10-11 20:57:53', 75000.00, 'pendiente', 'Bleidis Cervantes', '', NULL, '', ''),
(49, 4, '2025-10-12 04:01:28', 75000.00, 'pendiente', 'Bleidis', 'bleiscervantes29@gmail.com', NULL, '', ''),
(50, 48, '2025-10-11 21:04:50', 284000.00, 'pendiente', 'laue', 'laue29@gmail.com', NULL, '', ''),
(51, 48, '2025-10-12 04:05:08', 284000.00, 'pendiente', 'Andres', 'carlosdevsanchez@gmail.com', NULL, '', ''),
(52, 4, '2025-10-11 21:06:21', 170000.00, 'pendiente', 'laue', 'laue29@gmail.com', NULL, '', ''),
(53, 4, '2025-10-12 04:07:57', 215000.00, 'pendiente', 'Andres', 'carlosdevsanchez@gmail.com', NULL, '', ''),
(54, 4, '2025-10-11 21:11:43', 215000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', NULL, '', ''),
(55, 4, '2025-10-11 21:14:02', 170000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', NULL, '', ''),
(56, 4, '2025-10-11 21:14:28', 290000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', NULL, '', ''),
(57, 4, '2025-10-11 21:18:11', 215000.00, 'pendiente', 'Bleidis Cervantes', 'bleisyhojana@gmail.com', NULL, '', ''),
(58, 4, '2025-10-11 21:20:28', 215000.00, 'pendiente', 'Bleidis Cervantes', 'bleisyhojana@gmail.com', NULL, '', ''),
(59, 49, '2025-10-11 23:33:59', 360000.00, 'pendiente', 'Bleidis Cervantes', 'bleisyhojana@gmail.com', NULL, '', ''),
(60, 4, '2025-09-23 22:06:03', 450000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', NULL, '', ''),
(61, 4, '2025-09-23 22:06:03', 450000.00, 'pendiente', 'patricia', 'bleiscervantes29@gmail.com', NULL, '', ''),
(62, 57, '2025-09-23 22:06:03', 450000.00, 'pendiente', 'patricia', 'patricia2000@gmail.com', NULL, '', ''),
(64, 4, '2025-10-08 03:58:50', 150000.00, 'pendiente', 'yeimi johana', 'yeimipalma03@gmail.com', NULL, '', ''),
(65, 49, '2025-10-13 16:00:15', 189000.00, 'pendiente', 'Bleidis Cervantes', 'bleisyhojana@gmail.com', NULL, '', ''),
(66, 59, '2025-10-13 16:02:51', 240000.00, 'pendiente', 'andrea', 'andrea02@gmail.com', NULL, '', ''),
(68, 49, '2025-10-13 17:18:39', 309000.00, 'pendiente', 'andrea', 'andrea02@gmail.com', NULL, '', ''),
(69, 49, '2025-10-15 00:37:33', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', NULL, '', ''),
(70, 49, '2025-10-15 00:37:40', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', NULL, '', ''),
(80, 4, '2025-10-16 16:57:38', 642000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 45 #12-34', '', ''),
(81, 4, '2025-10-16 16:59:02', 642000.00, 'pendiente', 'Bleidis Cervantes', '', 'carrera 80 #35-30', '', ''),
(82, 4, '2025-10-16 17:24:25', 642000.00, 'pendiente', 'Bleidis Cervantes', '', 'calle 19', '', ''),
(83, 4, '2025-10-16 17:47:24', 642000.00, 'pendiente', 'Bleidis Cervantes', '', 'carrera 80 #35-30', '', ''),
(91, 4, '2025-10-16 20:01:03', 0.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 45 #12-34', '', ''),
(92, 4, '2025-10-16 20:42:31', 0.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 45 #12-34', '', ''),
(93, 23, '2025-10-16 20:50:54', 0.00, 'pendiente', 'paula', '', 'calle 19', '', ''),
(94, 23, '2025-10-16 22:08:25', 0.00, 'pendiente', 'paula', '', 'calle 19 28', '', ''),
(95, 23, '2025-10-17 13:20:22', 0.00, 'pendiente', 'paula', '', 'Calle 45 #12-34', '', ''),
(96, 23, '2025-10-24 17:20:35', 0.00, 'pendiente', 'paula', '', 'carrera 80 #35-30', '', ''),
(97, 4, '2025-10-24 18:56:30', 0.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 45 #12-34', '', ''),
(98, 4, '2025-10-24 18:59:37', 0.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 45 #12-34', '', ''),
(101, 4, '2025-10-24 19:06:22', 0.00, 'pendiente', 'Bleidis Cervantes', '', 'calle 19', '', ''),
(103, 4, '2025-10-24 20:50:53', 75000.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', NULL, '', ''),
(104, 4, '2025-10-24 20:52:55', 75000.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', NULL, '', ''),
(105, 4, '2025-10-24 20:58:21', 0.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', NULL, '', ''),
(106, 4, '2025-10-24 21:10:40', 0.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', NULL, '', ''),
(107, 4, '2025-10-24 21:26:01', 0.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', NULL, '', ''),
(108, 4, '2025-10-24 21:27:54', 0.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', NULL, '', ''),
(109, 4, '2025-10-24 21:28:19', 0.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', NULL, '', ''),
(110, 4, '2025-10-24 21:37:28', 0.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', NULL, '', ''),
(111, 4, '2025-10-24 21:43:52', 0.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', NULL, '', ''),
(112, 4, '2025-10-24 21:57:04', 0.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', NULL, '', ''),
(113, 4, '2025-10-24 22:06:47', 435000.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', NULL, '', ''),
(114, 4, '2025-10-24 22:15:50', 75000.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', 'calle 19', '', ''),
(115, 4, '2025-10-24 22:15:57', 120000.00, 'pendiente', 'Bleidis Cervantes', 'cliente@ejemplo.com', 'calle 19', '', ''),
(116, 4, '2025-10-25 00:30:39', 0.00, 'pendiente', 'Cliente', 'cliente@ejemplo.com', NULL, '', ''),
(117, 4, '2025-10-25 00:32:28', 0.00, 'pendiente', 'Cliente', 'cliente@ejemplo.com', NULL, '', ''),
(118, 4, '2025-10-25 00:35:55', 0.00, 'pendiente', 'Cliente', 'cliente@ejemplo.com', NULL, '', ''),
(119, 4, '2025-10-25 00:37:14', 0.00, 'pendiente', 'Cliente', 'cliente@ejemplo.com', NULL, '', ''),
(120, 4, '2025-10-25 00:37:57', 0.00, 'pendiente', 'Cliente', 'cliente@ejemplo.com', NULL, '', ''),
(121, 4, '2025-10-25 00:38:40', 0.00, 'pendiente', 'Cliente', 'cliente@ejemplo.com', NULL, '', ''),
(122, 4, '2025-10-25 00:58:59', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(123, 4, '2025-10-25 01:01:13', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(124, 4, '2025-10-25 01:01:44', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(125, 4, '2025-10-25 01:07:17', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(126, 4, '2025-10-25 01:07:58', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(127, 4, '2025-10-25 01:21:30', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(128, 4, '2025-10-25 01:29:06', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(129, 4, '2025-10-25 01:29:34', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(130, 4, '2025-10-25 01:29:42', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(131, 4, '2025-10-25 01:35:08', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(132, 4, '2025-10-25 01:35:21', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(133, 4, '2025-10-25 01:35:30', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(134, 4, '2025-10-25 01:37:20', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(135, 4, '2025-10-25 01:39:35', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(136, 4, '2025-10-25 01:39:47', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(137, 4, '2025-10-25 01:39:50', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(138, 4, '2025-10-25 01:40:20', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(139, 4, '2025-10-25 01:40:28', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(140, 4, '2025-10-25 01:41:21', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(141, 4, '2025-10-25 01:47:44', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(142, 4, '2025-10-25 01:48:13', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(143, 4, '2025-10-25 01:48:22', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(144, 4, '2025-10-25 01:48:57', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(145, 4, '2025-10-25 02:01:18', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(146, 4, '2025-10-25 02:06:06', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(147, 4, '2025-10-25 02:32:45', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(148, 4, '2025-10-25 02:43:18', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(149, 4, '2025-10-25 02:52:16', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(150, 4, '2025-10-25 02:59:09', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(151, 4, '2025-10-25 03:20:31', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(152, 4, '2025-10-25 03:20:46', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(153, 4, '2025-10-25 03:20:57', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(154, 4, '2025-10-26 22:00:38', 0.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(155, 4, '2025-10-26 22:09:48', 180000.00, 'pendiente', '', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Medellín', '0'),
(156, NULL, '2025-10-26 22:16:52', 180000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(157, 4, '2025-10-26 22:21:28', 180000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(158, 4, '2025-10-26 22:21:43', 180000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(159, NULL, '2025-10-26 22:25:44', 180000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(160, NULL, '2025-10-26 22:26:23', 180000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(161, NULL, '2025-10-26 22:26:49', 180000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(162, NULL, '2025-10-26 22:33:44', 180000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(163, NULL, '2025-10-26 22:37:17', 180000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Tarjeta'),
(164, NULL, '2025-10-26 22:38:07', 180000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(165, NULL, '2025-10-26 23:01:08', 180000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(166, NULL, '2025-10-26 23:20:52', 309000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(167, NULL, '2025-10-26 23:23:20', 120000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(168, NULL, '2025-10-26 23:23:47', 189000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(169, NULL, '2025-10-27 00:37:01', 567000.00, 'pendiente', 'Administrador ', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(170, NULL, '2025-10-27 01:53:49', 975000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(171, NULL, '2025-10-27 19:35:23', 120000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(172, NULL, '2025-10-27 20:05:53', 280000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(173, NULL, '2025-10-27 20:28:39', 235000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(174, NULL, '2025-10-27 20:31:24', 687000.00, 'pendiente', 'Administrador ', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(175, NULL, '2025-10-27 20:52:07', 189000.00, 'pendiente', 'Administrador ', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(176, NULL, '2025-10-27 21:39:26', 404000.00, 'pendiente', 'Administrador ', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(177, NULL, '2025-10-27 21:52:58', 120000.00, 'pendiente', 'Administrador ', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(178, NULL, '2025-10-27 21:53:08', 120000.00, 'pendiente', 'Administrador ', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(179, NULL, '2025-10-27 21:53:29', 120000.00, 'pendiente', 'Administrador ', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(180, NULL, '2025-10-27 21:54:15', 120000.00, 'pendiente', 'Administrador ', '', 'Calle 19 #1D 29', 'Medellín', 'Transferencia'),
(181, NULL, '2025-10-27 21:55:31', 120000.00, 'pendiente', 'Administrador ', '', 'Calle 19 #1D 29', 'Medellín', 'Transferencia'),
(182, NULL, '2025-10-27 22:05:30', 205000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(183, NULL, '2025-10-27 22:11:53', 355000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(184, NULL, '2025-10-27 22:18:14', 610000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(185, NULL, '2025-10-27 22:20:28', 730000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(186, NULL, '2025-10-27 22:21:25', 805000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(187, NULL, '2025-10-27 22:51:43', 290000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(188, NULL, '2025-10-27 23:02:42', 120000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(189, NULL, '2025-10-27 23:03:27', 120000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(190, NULL, '2025-10-27 23:37:28', 315000.00, 'pendiente', 'Bleidis Cervantes', '', 'Calle 19 #1D 29', 'Medellín', 'Efectivo'),
(191, 4, '2025-10-27 23:42:17', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(192, 4, '2025-10-27 23:50:04', 75000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(193, 4, '2025-10-27 23:51:36', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(194, 4, '2025-10-28 00:12:41', 463000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(195, 4, '2025-10-28 01:28:06', 300000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(196, 4, '2025-10-28 01:42:22', 463000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(197, 4, '2025-10-28 18:56:00', 567000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(198, 4, '2025-10-28 19:11:43', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(199, 4, '2025-10-28 19:20:38', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(200, NULL, '2025-10-28 22:10:25', 189000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(201, 23, '2025-10-28 22:16:58', 120000.00, 'pendiente', 'paula', 'paula@tiendaplus.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(202, 23, '2025-10-28 22:17:54', 189000.00, 'pendiente', 'paula', 'paula@tiendaplus.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(203, 23, '2025-10-28 22:30:18', 309000.00, 'pendiente', 'paula', 'paula@tiendaplus.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(204, 23, '2025-10-28 22:34:34', 360000.00, 'pendiente', 'paula', 'paula@tiendaplus.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(205, 23, '2025-10-28 22:42:05', 120000.00, 'pendiente', 'paula', 'paula@tiendaplus.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(206, 23, '2025-10-28 22:44:09', 75000.00, 'pendiente', 'paula', 'paula@tiendaplus.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(207, 64, '2025-10-28 22:59:37', 189000.00, 'pendiente', 'Laura Gómez', 'laugomez02@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(208, 4, '2025-10-29 01:08:25', 270000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(209, NULL, '2025-10-29 16:51:53', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(210, NULL, '2025-10-29 20:04:35', 189000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(211, NULL, '2025-10-30 18:54:14', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Efectivo'),
(212, 4, '2025-10-30 20:53:39', 264000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(213, 4, '2025-10-30 20:54:56', 264000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(214, 4, '2025-10-30 20:55:26', 75000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(215, 4, '2025-10-30 21:01:32', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(216, 4, '2025-10-30 21:01:51', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(217, 4, '2025-10-30 21:15:46', 120000.00, 'pendiente', 'Bleidis Cervantes ', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Tarjeta de crédito'),
(218, 4, '2025-10-30 21:19:25', 120000.00, 'pendiente', 'Bleidis Cervantes ', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Tarjeta de crédito'),
(219, 4, '2025-10-30 22:05:44', 195000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'Calle 19 #1D 29', 'Laureles', 'Tarjeta de crédito'),
(220, 4, '2025-10-30 22:19:11', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(221, 4, '2025-10-30 22:26:30', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(222, 4, '2025-10-30 22:26:57', 189000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(223, 4, '2025-10-30 22:27:14', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(224, 4, '2025-10-30 22:27:25', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(225, 4, '2025-10-30 22:28:36', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(226, 4, '2025-10-30 22:41:16', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(227, 4, '2025-10-30 22:41:38', 189000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(228, 4, '2025-10-30 23:38:46', 240000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(229, 4, '2025-10-30 23:39:06', 189000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(230, 69, '2025-10-30 23:44:02', 360000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes200229@icloud.com', 'No especificada', 'No especificada', 'Pendiente'),
(231, 69, '2025-10-30 23:47:52', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes200229@icloud.com', 'No especificada', 'No especificada', 'Pendiente'),
(232, 69, '2025-10-31 00:10:27', 189000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes200229@icloud.com', 'No especificada', 'No especificada', 'Pendiente'),
(233, 69, '2025-10-31 00:11:21', 360000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes200229@icloud.com', 'No especificada', 'No especificada', 'Pendiente'),
(234, 69, '2025-10-31 00:17:46', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes200229@icloud.com', 'No especificada', 'No especificada', 'Pendiente'),
(235, 69, '2025-10-31 00:19:04', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes200229@icloud.com', 'No especificada', 'No especificada', 'Pendiente'),
(236, 69, '2025-10-31 00:21:03', 75000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes200229@icloud.com', 'No especificada', 'No especificada', 'Pendiente'),
(237, 69, '2025-10-31 01:29:33', 189000.00, 'pendiente', 'Bleidis Cervantes', 'bleiscervantes200229@icloud.com', 'No especificada', 'No especificada', 'Pendiente'),
(238, 4, '2025-10-31 02:27:00', 882000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(239, 4, '2025-10-31 02:28:25', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(240, 4, '2025-10-31 02:28:47', 189000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(241, 4, '2025-10-31 16:35:13', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(242, 4, '2025-10-31 18:38:07', 75000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(243, 4, '2025-10-31 18:56:25', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(244, 4, '2025-10-31 18:56:57', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(245, 4, '2025-10-31 19:04:38', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(246, 4, '2025-10-31 19:05:06', 150000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(247, 4, '2025-10-31 19:09:52', 414000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'No especificada', 'No especificada', 'Pendiente'),
(248, 4, '2025-10-31 19:14:56', 75000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'carrera 80 #35-30', 'Barranquilla', 'Pendiente'),
(249, 4, '2025-10-31 19:15:39', 75000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'carrera 80 #35-30', 'Barranquilla', 'Pendiente'),
(250, 4, '2025-10-31 19:19:04', 120000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'Calle 19 #1D 29', 'Laureles', 'Pendiente'),
(251, 4, '2025-10-31 19:37:56', 75000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', 'Calle 19 #1D 29', 'Laureles', 'Pendiente'),
(252, 71, '2025-10-31 20:36:23', 75000.00, 'pendiente', 'manuela sanchez', 'manuela29@gmail.com', 'Calle 19 #1D 29', 'Laureles', 'Pendiente'),
(253, 49, '2025-11-10 22:18:35', 255000.00, 'pendiente', 'Administrador ', 'admin@tiendaplus.com', '', '', 'Pendiente'),
(254, 49, '2025-11-11 22:16:11', 274000.00, 'pendiente', 'Administrador ', 'admin@tiendaplus.com', '', '', 'Pendiente'),
(255, 4, '2025-11-11 22:27:09', 195000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', '', '', 'Pendiente'),
(256, 4, '2025-11-11 23:02:01', 270000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', '', '', 'Pendiente'),
(257, 4, '2025-11-13 15:59:01', 225000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', '', '', 'Pendiente'),
(258, 4, '2025-11-13 16:19:29', 75000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', '', '', 'Pendiente'),
(259, 4, '2025-11-13 16:32:16', 75000.00, 'pendiente', 'Bleidis Cervantes', 'bleis@tiendaplus.com', '', '', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `categoria_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `destacado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `stock`, `categoria_id`, `fecha_creacion`, `destacado`) VALUES
(1, 'Blusa', 'Rosada ', 75000.00, '', 10, NULL, '2025-09-23 19:48:21', 0),
(2, 'Vestido', 'Estampado', 120000.00, '', 6, NULL, '2025-09-23 19:49:10', 0),
(3, 'Pantalón ', 'azul\r\n', 189000.00, '', 12, NULL, '2025-10-05 02:34:41', 0),
(4, 'Pantalón ', 'azul\r\n', 189000.00, '', 12, NULL, '2025-10-05 02:34:45', 0),
(6, 'crop top ', 'blanco \r\nazul \r\nnegro', 85000.00, '', 0, NULL, '2025-10-05 02:36:13', 0),
(7, 'oversize', 'basico', 95000.00, '', 0, NULL, '2025-10-05 02:36:16', 0),
(8, 'crop top ', 'blanco \r\nazul \r\nnegro', 85000.00, '', 0, NULL, '2025-10-05 02:36:16', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `comentario` text DEFAULT NULL,
  `calificacion` int(11) DEFAULT NULL CHECK (`calificacion` between 1 and 5),
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resenas`
--

INSERT INTO `resenas` (`id`, `usuario_id`, `producto_id`, `comentario`, `calificacion`, `fecha`) VALUES
(4, 20, 1, 'bueno', NULL, '2025-10-09 14:21:05'),
(6, 4, 3, 'excelente', NULL, '2025-10-09 14:21:05'),
(8, 36, 7, 'excelente', NULL, '2025-10-09 17:23:08'),
(14, 49, 1, 'bueno', 4, '2025-10-30 21:00:15'),
(15, 49, 3, 'excelente calidad', 5, '2025-10-31 15:39:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','cliente') DEFAULT 'cliente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `fecha_creacion`) VALUES
(2, 'Bleidis Cervantes', 'bleis@gamil.com', '$2y$10$Incd/Vd4/ACi/DMyUvfD0evlWWgiJEpitmEQ3qKKOsCIaNG0nYnJy', 'cliente', '2025-09-25 23:52:46'),
(4, 'Bleidis Cervantes', 'bleis@tiendaplus.com', '$2y$10$doSD6GrQksWMZp29IC4Oeu2eVS3JfVsHj8Kw5it1pnt5OZwcmeec6', 'cliente', '2025-09-26 22:32:03'),
(5, 'carlos', 'carlos@tiendaplus.com', '$2y$10$6fJx8.pEtqqInjESsFR./errR380gMmXBfE7CFupwD9/xV8STvlr6', 'cliente', '2025-09-29 20:55:02'),
(15, 'Breiner', 'breinercervantes14@gmail.com', '$2y$10$Uirie2ct18IzZ252KRdgAulAhKzXMkQG6E8NIeN/9U6rT2GDkAmWS', 'cliente', '2025-09-30 21:52:27'),
(17, 'Andres', 'carlosdevsanchez@gmail.com', '$2y$10$8oEppgMEULmwkm/eFq2.Su4614g54PG4kstI46WaW.mfmB00oP/f.', 'cliente', '2025-09-30 21:53:55'),
(18, 'Nestor', 'nestorcervantescharris@gmail.com', '$2y$10$SWZmO/jwK1AujywuZ3wPEeFHmXt2pWGCjI5YS2SiR.JPAsh2lYoUK', 'cliente', '2025-10-01 00:33:23'),
(19, 'Andres', 'andres@gmail.com', '$2y$10$evmq.6zLoth.HJNBIBXFn.Tb98a7MStzYr1sP9KvbvQvMkSxx22tC', 'cliente', '2025-10-01 01:33:25'),
(20, 'Andres', 'andres2345@gmail.com', '$2y$10$Bvad.EEOESMCaI21glRMaO0Ye7b6biUkWme85c5LEOdSdoK3WgJwe', 'cliente', '2025-10-03 19:55:23'),
(21, 'patricia', 'patricia@gmail.com', '$2y$10$JhsPGTWyYBoA7d0E4UTa4ePWGY3tUS8iiRoCHAJ1XykwE9Q1kyV5K', 'cliente', '2025-10-04 00:58:10'),
(22, 'Sandra', 'sandra@gmail.com', '$2y$10$4ORAp2u8dvjzcbTs99O9pe4d55pSsMcBkWHq.LcYrrnCKylUxihL2', 'cliente', '2025-10-04 23:35:37'),
(23, 'paula', 'paula@tiendaplus.com', '$2y$10$YZoZVM4/AJiw6MJ4MRw7MOMmwd0XQzSAxfrl18/2MG9wHaEuU8bcG', 'cliente', '2025-10-06 21:20:05'),
(24, 'maria', 'maria@gmail.com', '123456', 'cliente', '2025-10-06 21:36:04'),
(26, 'Laura Gómez', 'laugomez@gmail.com', '123456', 'cliente', '2025-10-07 16:54:21'),
(28, 'Andres', 'andres12@gmail.com', '$2y$10$RyTeCn3rAv9oJo7WUOVQnuXXfDR97CioGVTB76odLEN4CjpisSWMG', 'cliente', '2025-10-07 19:13:22'),
(36, 'yhojana', 'yhojana@gmail.com', '$2y$10$Incd/Vd4/ACi/DMyUvfD0evlWWgiJEpitmEQ3qKKOsCIaNG0nYnJy', 'cliente', '2025-09-23 22:06:03'),
(37, 'sanchez', 'sanchez@gmail.com', '$2y$10$zsjt//tqGOfXKBzjKgTT/eHjEgJEguifMAyeOyN.iAW0repfM83R2', 'cliente', '2025-10-09 22:39:00'),
(38, 'Laura Gómez', 'lau@gmail.com', '$2y$10$mCo.V1yQeRwP3ujH/8cUduddiRFq83Lx4evqS/IGCiZSImElP74u2', 'cliente', '2025-10-09 22:51:48'),
(42, 'Yovana', 'yovana@gmail.com', '$2y$10$Incd/Vd4/ACi/DMyUvfD0evlWWgiJEpitmEQ3qKKOsCIaNG0nYnJy', 'cliente', '2025-09-23 22:06:03'),
(43, 'Laura', 'lau16@gmail.com', '$2y$10$nyqkrP3F3ucn9NlaUocmlO2MTbaEcfR2nvtrotd2WgJrQgSr8BFU6', 'cliente', '2025-10-09 23:58:09'),
(44, 'sofia palma', 'sofiapalma@gamil.com', '$2y$10$Incd/Vd4/ACi/DMyUvfD0evlWWgiJEpitmEQ3qKKOsCIaNG0nYnJy', 'cliente', '2025-09-25 23:52:46'),
(47, 'andrea', 'andrea@gmail.com', '$2y$10$ynD78fP6JrPc6fgw7tPhP.RAr7EiLrTyoTVxd1j2Mu./XF5sRFOoG', 'cliente', '2025-10-10 22:02:54'),
(48, 'Laura Gómez', 'laugomez02@gmail.com', '$2y$10$XbZ1FgzaYSkHUtGPxLh3T.Enlo2bQmQ/Dam8aEQg7DkPGny39KNx2', 'cliente', '2025-10-11 02:13:40'),
(49, 'Administrador ', 'admin@tiendaplus.com', '123456', 'admin', '2025-10-11 16:02:13'),
(56, 'patricia ', 'patricia0000@gmail.com', '$2y$10$Incd/Vd4/ACi/DMyUvfD0evlWWgiJEpitmEQ3qKKOsCIaNG0nYnJy', 'cliente', '2025-10-05 02:36:13'),
(57, 'patricia cervantes ', 'patricia2000@gmail.com', '$2y$10$Incd/Vd4/ACi/DMyUvfD0evlWWgiJEpitmEQ3qKKOsCIaNG0nYnJy', 'cliente', '2025-10-05 02:36:13'),
(58, 'Yeimi Yojana', 'yeimipalma01@gmail.com', '$2y$10$Incd/Vd4/ACi/DMyUvfD0evlWWgiJEpitmEQ3qKKOsCIaNG0nYnJy', 'cliente', '2025-10-05 02:36:13'),
(59, 'andrea', 'andrea02@gmail.com', '$2y$10$oQVKY7ZVVfvkoPUkIXixNusR/WUyLAoU.fBe0PfXJan0BMvmOFSiq', 'cliente', '2025-10-13 16:01:56'),
(61, 'Yeimipardo', 'yeimipalma03@gmail.com', '$2y$10$Incd/Vd4/ACi/DMyUvfD0evlWWgiJEpitmEQ3qKKOsCIaNG0nYnJy', 'cliente', '2025-10-05 02:36:13'),
(62, 'Bleidis Cervantes', 'bleis23@hotmail.com', '$2y$10$JER6FaYG46rL9OigfdWQ7eEQj5zDAPthEbk5QpNyhxqM72WhWaNLW', 'cliente', '2025-10-13 21:12:28'),
(64, 'Laura Gómez', 'lau12@gmail.com', '$2y$10$DA0f7D82gAZ/SK11ty33xOqj/DjZfZ/tbXrVycKvmVisovSYshuhW', 'cliente', '2025-10-28 22:46:04'),
(68, 'carlos', 'carlos19972107sanchez@gmail.com', '$2y$10$adE3dLtpqClvGvcc9LiBJuKQnbT/Fdn48Cw4wWN5UOe14tPGPxrKG', 'cliente', '2025-10-30 19:37:35'),
(69, 'Bleidis Cervantes', 'bleiscervantes200229@icloud.com', '$2y$10$sTO/0bsB7U35Zxi1sGeC8.5CnG7VOETTO23Abvb2EpG2.t8I2kvQq', 'cliente', '2025-10-30 23:40:18'),
(70, 'manuela', 'manuela123@gmail.com', '$2y$10$JSAMGt4Bz84SdBzEUPtTqOfjbXaxX3S9UMkFvTngTU99mIafVG6o2', 'cliente', '2025-10-31 20:32:36'),
(71, 'manuela sanchez', 'manuela29@gmail.com', '$2y$10$Jj3D6ScCLpOtgNu0XGvQ3OfQe7ivwJnXHo.gbQ6VTKAlYWk5Z1Pb2', 'cliente', '2025-10-31 20:33:50');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `carrito_ibfk_1` (`usuario_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_pedido_ibfk_1` (`pedido_id`),
  ADD KEY `detalle_pedido_ibfk_2` (`producto_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_ibfk_1` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resenas_ibfk_1` (`usuario_id`),
  ADD KEY `resenas_ibfk_2` (`producto_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=302;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resenas_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
