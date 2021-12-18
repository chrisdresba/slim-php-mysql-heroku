-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 13-12-2021 a las 01:00:25
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `Comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `idEncuesta` int(11) NOT NULL,
  `codigoMesa` varchar(5) NOT NULL,
  `codigoPedido` varchar(11) NOT NULL,
  `puntMesa` int(11) NOT NULL,
  `puntResto` int(11) NOT NULL,
  `puntMozo` int(11) NOT NULL,
  `puntCocinero` int(11) NOT NULL,
  `experiencia` varchar(66) NOT NULL,
  `fecha` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `encuesta`
--

INSERT INTO `encuesta` (`idEncuesta`, `codigoMesa`, `codigoPedido`, `puntMesa`, `puntResto`, `puntMozo`, `puntCocinero`, `experiencia`, `fecha`) VALUES
(1, '8', 'AA5581', 8, 7, 9, 8, 'Una muy buena atencion', '2021-12-07'),
(2, '8', 'AA55780', 8, 7, 9, 8, 'Una muy buena atencion', '2021-12-08'),
(3, '7', 'AA5581', 7, 6, 6, 7, 'Demora en la atencion', '2021-12-09'),
(4, '2', 'AA5582', 7, 5, 6, 5, 'Atencion Regular', '2021-12-10'),
(5, '2', 'AA5583', 8, 10, 9, 8, 'Una muy buena atencion', '2021-12-11'),
(6, '3', 'AA5584', 8, 8, 9, 8, 'Una muy buena atencion', '2021-12-11'),
(7, '4', 'AA5585', 7, 6, 6, 7, 'Demora en la atencion', '2021-12-12'),
(8, '5', 'AA5590', 7, 5, 6, 5, 'Atencion Regular', '2021-12-12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `fecha` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `usuario`, `fecha`) VALUES
(1, 'jose@gmail.com.ar', '2021-12-07 08:05:10'),
(2, 'jose@gmail.com.ar', '2021-12-07 08:51:19'),
(3, 'dagost@gmail.com.ar', '2021-12-07 08:53:58'),
(4, 'ericag@gmail.com.ar', '2021-12-07 09:47:36'),
(5, 'ericag@gmail.com.ar', '2021-12-07 09:52:43'),
(6, 'dagost@gmail.com.ar', '2021-12-07 11:25:58'),
(7, 'dagost@gmail.com.ar', '2021-12-07 11:28:09'),
(8, 'dagost@gmail.com.ar', '2021-12-07 11:30:42'),
(9, 'dagost@gmail.com.ar', '2021-12-07 11:31:05'),
(10, 'dagost@gmail.com.ar', '2021-12-07 11:32:55'),
(11, 'dagost@gmail.com.ar', '2021-12-07 11:33:46'),
(12, 'dagost@gmail.com.ar', '2021-12-07 12:01:17'),
(13, 'jose@gmail.com.ar', '2021-12-10 11:03:43'),
(14, 'juliof@gmail.com.ar', '2021-12-10 11:03:59'),
(15, 'ericag@gmail.com.ar', '2021-12-10 11:04:03'),
(16, 'aperez@gmail.com.ar', '2021-12-10 11:04:07'),
(17, 'dagost@gmail.com.ar', '2021-12-10 11:04:11'),
(18, 'dagost@gmail.com.ar', '2021-12-11 02:09:27'),
(19, 'jose@gmail.com.ar', '2021-12-11 10:43:18'),
(20, 'dagost@gmail.com.ar', '2021-12-11 11:19:17');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `idMesa` int(11) NOT NULL,
  `codigo` varchar(5) DEFAULT NULL,
  `estado` varchar(30) DEFAULT NULL,
  `fechaInicio` varchar(20) DEFAULT NULL,
  `fechaFinalizado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`idMesa`, `codigo`, `estado`, `fechaInicio`, `fechaFinalizado`) VALUES
(1, 'A5GH1', 'Cerrada', '2021-11-09', '2021-11-09'),
(2, 'GR84T', 'Abierta', '2021-11-10', '2021-12-05'),
(3, 'H4Q5W', 'Cerrada', '2021-11-10', '2021-12-06'),
(4, 'L87MY', 'Abierta', '2021-11-09', '2021-12-05'),
(5, 'P78BA', 'Cerrada', '2021-11-09', '2021-12-06'),
(6, 'ZQ4W8', 'Abierta', '2021-11-09', '2021-12-05'),
(7, 'I8Y5T', 'Cerrada', '2021-11-09', '2021-12-05'),
(8, '99QWE', 'Abierta', '2021-11-09', '2021-12-04'),
(9, 'JG2F2', 'Cerrada', '2021-11-09', '2021-12-03'),
(10, 'R7T9Y', 'Abierta', '2021-11-09', '2021-12-04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operaciones`
--

CREATE TABLE `operaciones` (
  `idOperacion` int(11) NOT NULL,
  `idMesa` varchar(40) NOT NULL,
  `importe` float NOT NULL,
  `fechaCreacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `operaciones`
--

INSERT INTO `operaciones` (`idOperacion`, `idMesa`, `importe`, `fechaCreacion`) VALUES
(1, '8', 1000, '2021-12-07'),
(2, '6', 800, '2021-12-09'),
(3, '7', 2050, '2021-12-10'),
(4, '8', 2850, '2021-12-10'),
(5, '8', 3550, '2021-12-11'),
(6, '2', 3050, '2021-12-11'),
(7, '2', 1950, '2021-12-12'),
(8, '5', 2000, '2021-12-12'),
(9, '4', 2850, '2021-12-12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedido` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idMesa` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `unidades` int(11) NOT NULL,
  `nombreCliente` varchar(40) NOT NULL,
  `horaInicio` varchar(20) DEFAULT NULL,
  `horaFinalizado` varchar(20) DEFAULT NULL,
  `estado` varchar(20) NOT NULL,
  `fecha` varchar(20) DEFAULT NULL,
  `foto` varchar(45) DEFAULT NULL,
  `codigoPedido` varchar(20) DEFAULT NULL,
  `tiempoEspera` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procedimientos`
--

CREATE TABLE `procedimientos` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `seccion` varchar(50) DEFAULT NULL,
  `fecha` varchar(50) DEFAULT NULL,
  `idProducto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProducto` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `seccion` varchar(40) NOT NULL,
  `precio` float NOT NULL,
  `fechaCarga` varchar(20) DEFAULT NULL,
  `fechaModificacion` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idProducto`, `nombre`, `seccion`, `precio`, `fechaCarga`, `fechaModificacion`) VALUES
(1, 'Mojito', 'Tragos', 350, '2021-11-09', '2021-11-09'),
(2, 'Gin Tonic', 'Tragos', 320, '2021-11-09', '2021-11-09'),
(3, 'Daikiri', 'Tragos', 400, '2021-11-09', '2021-11-09'),
(4, 'Champagne Baron', 'Tragos', 5800, '2021-11-09', '2021-11-09'),
(5, 'Andes Ipa', 'Choperas', 280, '2021-11-09', '2021-11-09'),
(6, 'Imperial Apa', 'Choperas', 280, '2021-11-09', '2021-11-09'),
(7, 'Scotch', 'Choperas', 290, '2021-11-09', '2021-11-09'),
(8, 'Corona', 'Choperas', 300, '2021-11-09', '2021-11-09'),
(9, 'Heineken', 'Choperas', 300, '2021-11-09', '2021-11-09'),
(10, 'Picada para dos', 'Cocina', 1200, '2021-11-09', '2021-11-09'),
(11, 'Milanesa a caballo', 'Cocina', 850, '2021-11-09', '2021-11-09'),
(12, 'Pizza Especial', 'Cocina', 690, '2021-11-09', '2021-11-09'),
(13, 'Hamburguesa de garbanzo', 'Cocina', 650, '2021-11-09', '2021-11-09'),
(14, 'Hamburguesa Delta', 'Cocina', 600, '2021-11-09', '2021-11-09'),
(15, 'Ensalada Cesar', 'Cocina', 580, '2021-11-09', '2021-11-09'),
(16, 'Bastones de Muzzarella', 'Cocina', 490, '2021-11-09', '2021-11-09'),
(17, 'Flan con dulce', 'Candy Bar', 250, '2021-11-09', '2021-11-09'),
(18, 'Volcan', 'Candy Bar', 450, '2021-11-09', '2021-11-09'),
(19, 'Chocotorta', 'Candy Bar', 390, '2021-11-09', '2021-11-09'),
(20, 'Rogel', 'Candy Bar', 320, '2021-11-09', '2021-11-09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellido` varchar(40) NOT NULL,
  `usuario` varchar(40) NOT NULL,
  `clave` varchar(40) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `sector` varchar(20) DEFAULT NULL,
  `fechaAlta` varchar(20) DEFAULT NULL,
  `fechaBaja` varchar(20) DEFAULT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `apellido`, `usuario`, `clave`, `tipo`, `sector`, `fechaAlta`, `fechaBaja`, `estado`) VALUES
(1, 'Jose', 'Hernandes', 'jose@gmail.com.ar', '1234', 'Mozo', '', '2021-11-09', '', 'Activo'),
(2, 'Bruno', 'Roncaglia', 'brunor@gmail.com.ar', '1234', 'Mozo', '', '2021-11-09', '', 'Activo'),
(3, 'Esteban', 'Diaz', 'estebandiaz@gmail.com.ar', '1234', 'Mozo', '', '2021-11-09', '', 'Activo'),
(4, 'Ana', 'Paez', 'paez@gmail.com.ar', '1234', 'Mozo', '', '2021-11-09', '', 'Activo'),
(5, 'Julio', 'Fernandes', 'juliof@gmail.com.ar', '1234', 'Bartender', 'Tragos', '2021-11-09', '', 'Activo'),
(6, 'Maria', 'Godoy', 'mgodoy@gmail.com.ar', '1234', 'Bartender', 'Tragos', '2021-11-09', '', 'Activo'),
(7, 'Sebastian', 'Diaz', 'sebasdiaz@gmail.com.ar', '1234', 'Cerveceros', 'Choperas', '2021-11-09', '', 'Activo'),
(8, 'Erica', 'Garcia', 'ericag@gmail.com.ar', '1234', 'Cerveceros', 'Choperas', '2021-11-09', '', 'Activo'),
(9, 'Antonio', 'Perez', 'aperez@gmail.com.ar', '1234', 'Cocinero', 'Cocina', '2021-11-09', '', 'Activo'),
(10, 'Micaela', 'Burdisso', 'micburd@gmail.com.ar', '1234', 'Cocinero', 'Cocina', '2021-11-09', '', 'Activo'),
(11, 'Vanesa', 'Lopez', 'vlopez@gmail.com.ar', '1234', 'Cocinero', 'Cocina', '2021-11-09', '', 'Activo'),
(12, 'Gonzalo', 'Aranda', 'aranda@gmail.com.ar', '1234', 'Cocinero', 'Cocina', '2021-11-09', '', 'Activo'),
(13, 'Emiliano', 'Hernandez', 'ehernandez@gmail.com.ar', '4321', 'Socio', '', '2021-11-09', '', 'Activo'),
(14, 'Florencia', 'Dagostino', 'dagost@gmail.com.ar', '4321', 'Socio', '', '2021-11-09', '', 'Activo'),
(15, 'Guadalupe', 'Hernandez', 'ghernandez@gmail.com.ar', '4321', 'Socio', '', '2021-11-09', '', 'Activo'),
(17, 'Maria', 'Alvarez', 'alvarez@gmail.com', '1234', 'Bartender', 'Tragos', '2021-11-11', NULL, 'Activo'),
(18, 'Julio', 'Monopoli', 'monopoli@gmail.com', '1234', 'Bartender', 'Tragos', '2021-12-06', NULL, 'Activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`idEncuesta`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`idMesa`);

--
-- Indices de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  ADD PRIMARY KEY (`idOperacion`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedido`);

--
-- Indices de la tabla `procedimientos`
--
ALTER TABLE `procedimientos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `idEncuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `idMesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  MODIFY `idOperacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `procedimientos`
--
ALTER TABLE `procedimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
