-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 30-10-2025 a las 18:46:25
-- Versión del servidor: 10.6.20-MariaDB-cll-lve
-- Versión de PHP: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `iestlarecoletaed_registro_egresados`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto_egresados`
--

CREATE TABLE `contacto_egresados` (
  `id_contacto` int(11) NOT NULL,
  `id_egresado` int(11) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresados`
--

CREATE TABLE `egresados` (
  `id_egresado` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` enum('Masculino','Femenino','Otro') NOT NULL,
  `estado_civil` enum('Soltero(a)','Casado(a)','Divorciado(a)','Viudo(a)','Unión Libre') NOT NULL,
  `id_programa` int(11) NOT NULL,
  `año_ingreso` int(11) NOT NULL,
  `año_egreso` int(11) NOT NULL,
  `estado_actual` enum('EGRESADO','TITULADO','CERTIFICADO','EN PROCESO') DEFAULT 'EGRESADO',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `egresados`
--

INSERT INTO `egresados` (`id_egresado`, `dni`, `nombres`, `apellidos`, `fecha_nacimiento`, `sexo`, `estado_civil`, `id_programa`, `año_ingreso`, `año_egreso`, `estado_actual`, `fecha_registro`, `fecha_actualizacion`, `telefono`, `email`, `direccion`) VALUES
(1, '42481769', 'Godofredo1', 'Sandoval Ramírez', '1980-11-08', 'Masculino', 'Casado(a)', 1, 2022, 2025, 'EGRESADO', '2025-10-21 18:02:26', '2025-10-22 01:01:41', '+51 987 654 321', 'godofredo.sandoval@email.com', 'Av. Principal 123, Lima, Perú'),
(2, '12345678', 'María Elena', 'Gonzales López', '1985-03-15', 'Femenino', 'Soltero(a)', 1, 2021, 2024, 'EGRESADO', '2025-10-21 18:03:31', '2025-10-22 01:00:21', '+51 955 444 333', 'maria.gonzales@email.com', 'Jr. Los Olivos 457'),
(3, '87654321', 'Carlos Alberto', 'Martínez Ríos', '1982-07-22', 'Masculino', 'Casado(a)', 1, 2020, 2023, 'TITULADO', '2025-10-21 18:03:31', '2025-10-21 18:03:31', '+51 944 333 222', 'carlos.martinez@email.com', 'Av. Libertad 789'),
(4, '34567893', 'Diego', 'Sandoval Ramírez', '1983-10-21', 'Masculino', 'Soltero(a)', 1, 2021, 2023, 'TITULADO', '2025-10-21 23:15:49', '2025-10-21 23:25:45', '951506863', 'godofredo@gmail.com', 'PROLONGACION VIVIENDA VILLA CERRILLOS ALBERTO FUJIMORI ZONA B MZ. A LT.1'),
(5, '34567895', 'rosendo', 'Sandoval Ramírez', '1980-11-08', 'Masculino', 'Casado(a)', 1, 2021, 2023, '', '2025-10-22 02:12:05', '2025-10-22 02:12:05', '951506863', 'admin@empresa.com', 'PROLONGACION VIVIENDA VILLA CERRILLOS ALBERTO FUJIMORI ZONA B MZ. A LT.1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas_estudio`
--

CREATE TABLE `programas_estudio` (
  `id_programa` int(11) NOT NULL,
  `nombre_programa` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `programas_estudio`
--

INSERT INTO `programas_estudio` (`id_programa`, `nombre_programa`, `descripcion`, `activo`, `fecha_creacion`) VALUES
(1, 'DISEÑO Y PROGRAMACIÓN WEB', 'Programa especializado en diseño y desarrollo web', 1, '2025-10-21 18:02:26'),
(2, 'MECÁNICA DE PRODUCCIÓN', 'mecánica de producción', 1, '2025-10-21 18:02:26'),
(3, 'MECÁNICA DE PRODUCCIÓN INDUSTRIAL', 'Mecánica de Producción Industrial', 1, '2025-10-21 18:02:26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contacto_egresados`
--
ALTER TABLE `contacto_egresados`
  ADD PRIMARY KEY (`id_contacto`),
  ADD KEY `id_egresado` (`id_egresado`);

--
-- Indices de la tabla `egresados`
--
ALTER TABLE `egresados`
  ADD PRIMARY KEY (`id_egresado`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `id_programa` (`id_programa`),
  ADD KEY `idx_dni` (`dni`),
  ADD KEY `idx_apellidos` (`apellidos`);

--
-- Indices de la tabla `programas_estudio`
--
ALTER TABLE `programas_estudio`
  ADD PRIMARY KEY (`id_programa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contacto_egresados`
--
ALTER TABLE `contacto_egresados`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `egresados`
--
ALTER TABLE `egresados`
  MODIFY `id_egresado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `programas_estudio`
--
ALTER TABLE `programas_estudio`
  MODIFY `id_programa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
