-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-10-2024 a las 06:52:31
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
-- Base de datos: `outsourcing1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `codigo` varchar(5) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras_estudiadas`
--

CREATE TABLE `carreras_estudiadas` (
  `prospecto` int(11) NOT NULL,
  `carrera` varchar(30) NOT NULL,
  `anioConcluido` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras_solicitadas`
--

CREATE TABLE `carreras_solicitadas` (
  `vacante` int(11) NOT NULL,
  `carrera` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificacion`
--

CREATE TABLE `certificacion` (
  `codigo` varchar(5) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `curso` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificaciones_obtenidas`
--

CREATE TABLE `certificaciones_obtenidas` (
  `prospecto` int(11) NOT NULL,
  `certificacion` varchar(5) NOT NULL,
  `fechaEmision` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificaciones_requeridas`
--

CREATE TABLE `certificaciones_requeridas` (
  `vacante` int(11) NOT NULL,
  `certificacion` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contrato`
--

CREATE TABLE `contrato` (
  `numero` int(11) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaCierre` date NOT NULL,
  `prospecto` int(11) NOT NULL,
  `vacante` int(11) NOT NULL,
  `tipo_contrato` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `codigo` varchar(5) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `descripcion` varchar(300) NOT NULL,
  `duracion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos_inscritos`
--

CREATE TABLE `cursos_inscritos` (
  `prospecto` int(11) NOT NULL,
  `curso` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `numero` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `ciudad` varchar(30) NOT NULL,
  `calle` varchar(30) NOT NULL,
  `numeroCalle` int(11) NOT NULL,
  `colonia` varchar(30) NOT NULL,
  `codigoPostal` int(11) NOT NULL,
  `nombreCont` varchar(30) NOT NULL,
  `primerApellidoCont` varchar(30) NOT NULL,
  `segundoApellidoCont` varchar(30) DEFAULT NULL,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluacion`
--

CREATE TABLE `evaluacion` (
  `numero` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `curso` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes_aplicados`
--

CREATE TABLE `examenes_aplicados` (
  `prospecto` int(11) NOT NULL,
  `evaluacion` int(11) NOT NULL,
  `calificacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `leccion`
--

CREATE TABLE `leccion` (
  `numero` varchar(5) NOT NULL,
  `titulo` varchar(30) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `contenido` varchar(5000) NOT NULL,
  `curso` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `membresia`
--

CREATE TABLE `membresia` (
  `numero` int(11) NOT NULL,
  `fechaVencimiento` date NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  `empresa` int(11) NOT NULL,
  `plan_suscripcion` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_suscripcion`
--

CREATE TABLE `plan_suscripcion` (
  `codigo` varchar(5) NOT NULL,
  `duracion` int(11) NOT NULL,
  `precio` decimal(5,2) NOT NULL,
  `precioMensual` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta`
--

CREATE TABLE `pregunta` (
  `numero` int(11) NOT NULL,
  `texto` varchar(200) NOT NULL,
  `evaluacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prospecto`
--

CREATE TABLE `prospecto` (
  `numero` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `primerApellido` varchar(30) NOT NULL,
  `segundoApellido` varchar(30) DEFAULT NULL,
  `fechaNacimiento` date NOT NULL,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requerimiento`
--

CREATE TABLE `requerimiento` (
  `numero` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `vacante` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta` (
  `numero` int(11) NOT NULL,
  `texto` varchar(200) NOT NULL,
  `es_correcta` tinyint(1) NOT NULL,
  `pregunta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `codigo` varchar(3) NOT NULL,
  `nombre` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_contrato`
--

CREATE TABLE `tipo_contrato` (
  `codigo` varchar(5) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `numero` int(11) NOT NULL,
  `correo` varchar(25) NOT NULL,
  `contrasenia` varchar(25) NOT NULL,
  `rol` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacante`
--

CREATE TABLE `vacante` (
  `numero` int(11) NOT NULL,
  `titulo` varchar(30) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `salario` int(11) DEFAULT NULL,
  `es_directo` tinyint(1) NOT NULL,
  `cantEmpleados` int(11) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaCierre` date NOT NULL,
  `diasRestantes` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `tipo_contrato` varchar(5) NOT NULL,
  `empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `carreras_estudiadas`
--
ALTER TABLE `carreras_estudiadas`
  ADD PRIMARY KEY (`prospecto`,`carrera`),
  ADD KEY `carrera` (`carrera`);

--
-- Indices de la tabla `carreras_solicitadas`
--
ALTER TABLE `carreras_solicitadas`
  ADD PRIMARY KEY (`vacante`,`carrera`),
  ADD KEY `carrera` (`carrera`);

--
-- Indices de la tabla `certificacion`
--
ALTER TABLE `certificacion`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `curso` (`curso`);

--
-- Indices de la tabla `certificaciones_obtenidas`
--
ALTER TABLE `certificaciones_obtenidas`
  ADD PRIMARY KEY (`prospecto`,`certificacion`),
  ADD KEY `certificacion` (`certificacion`);

--
-- Indices de la tabla `certificaciones_requeridas`
--
ALTER TABLE `certificaciones_requeridas`
  ADD PRIMARY KEY (`vacante`,`certificacion`),
  ADD KEY `certificacion` (`certificacion`);

--
-- Indices de la tabla `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `prospecto` (`prospecto`),
  ADD KEY `vacante` (`vacante`),
  ADD KEY `tipo_contrato` (`tipo_contrato`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `cursos_inscritos`
--
ALTER TABLE `cursos_inscritos`
  ADD PRIMARY KEY (`prospecto`,`curso`),
  ADD KEY `curso` (`curso`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `evaluacion`
--
ALTER TABLE `evaluacion`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `curso` (`curso`);

--
-- Indices de la tabla `examenes_aplicados`
--
ALTER TABLE `examenes_aplicados`
  ADD PRIMARY KEY (`prospecto`,`evaluacion`),
  ADD KEY `evaluacion` (`evaluacion`);

--
-- Indices de la tabla `leccion`
--
ALTER TABLE `leccion`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `curso` (`curso`);

--
-- Indices de la tabla `membresia`
--
ALTER TABLE `membresia`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `empresa` (`empresa`),
  ADD KEY `plan_suscripcion` (`plan_suscripcion`);

--
-- Indices de la tabla `plan_suscripcion`
--
ALTER TABLE `plan_suscripcion`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `evaluacion` (`evaluacion`);

--
-- Indices de la tabla `prospecto`
--
ALTER TABLE `prospecto`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `requerimiento`
--
ALTER TABLE `requerimiento`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `vacante` (`vacante`);

--
-- Indices de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `pregunta` (`pregunta`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `tipo_contrato`
--
ALTER TABLE `tipo_contrato`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `rol` (`rol`);

--
-- Indices de la tabla `vacante`
--
ALTER TABLE `vacante`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `tipo_contrato` (`tipo_contrato`),
  ADD KEY `empresa` (`empresa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contrato`
--
ALTER TABLE `contrato`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evaluacion`
--
ALTER TABLE `evaluacion`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `membresia`
--
ALTER TABLE `membresia`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prospecto`
--
ALTER TABLE `prospecto`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `requerimiento`
--
ALTER TABLE `requerimiento`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vacante`
--
ALTER TABLE `vacante`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carreras_estudiadas`
--
ALTER TABLE `carreras_estudiadas`
  ADD CONSTRAINT `carreras_estudiadas_ibfk_1` FOREIGN KEY (`prospecto`) REFERENCES `prospecto` (`numero`),
  ADD CONSTRAINT `carreras_estudiadas_ibfk_2` FOREIGN KEY (`carrera`) REFERENCES `carrera` (`codigo`);

--
-- Filtros para la tabla `carreras_solicitadas`
--
ALTER TABLE `carreras_solicitadas`
  ADD CONSTRAINT `carreras_solicitadas_ibfk_1` FOREIGN KEY (`vacante`) REFERENCES `vacante` (`numero`),
  ADD CONSTRAINT `carreras_solicitadas_ibfk_2` FOREIGN KEY (`carrera`) REFERENCES `carrera` (`codigo`);

--
-- Filtros para la tabla `certificacion`
--
ALTER TABLE `certificacion`
  ADD CONSTRAINT `certificacion_ibfk_1` FOREIGN KEY (`curso`) REFERENCES `curso` (`codigo`);

--
-- Filtros para la tabla `certificaciones_obtenidas`
--
ALTER TABLE `certificaciones_obtenidas`
  ADD CONSTRAINT `certificaciones_obtenidas_ibfk_1` FOREIGN KEY (`prospecto`) REFERENCES `prospecto` (`numero`),
  ADD CONSTRAINT `certificaciones_obtenidas_ibfk_2` FOREIGN KEY (`certificacion`) REFERENCES `certificacion` (`codigo`);

--
-- Filtros para la tabla `certificaciones_requeridas`
--
ALTER TABLE `certificaciones_requeridas`
  ADD CONSTRAINT `certificaciones_requeridas_ibfk_1` FOREIGN KEY (`vacante`) REFERENCES `vacante` (`numero`),
  ADD CONSTRAINT `certificaciones_requeridas_ibfk_2` FOREIGN KEY (`certificacion`) REFERENCES `certificacion` (`codigo`);

--
-- Filtros para la tabla `contrato`
--
ALTER TABLE `contrato`
  ADD CONSTRAINT `contrato_ibfk_1` FOREIGN KEY (`prospecto`) REFERENCES `prospecto` (`numero`),
  ADD CONSTRAINT `contrato_ibfk_2` FOREIGN KEY (`vacante`) REFERENCES `vacante` (`numero`),
  ADD CONSTRAINT `contrato_ibfk_3` FOREIGN KEY (`tipo_contrato`) REFERENCES `tipo_contrato` (`codigo`);

--
-- Filtros para la tabla `cursos_inscritos`
--
ALTER TABLE `cursos_inscritos`
  ADD CONSTRAINT `cursos_inscritos_ibfk_1` FOREIGN KEY (`prospecto`) REFERENCES `prospecto` (`numero`),
  ADD CONSTRAINT `cursos_inscritos_ibfk_2` FOREIGN KEY (`curso`) REFERENCES `curso` (`codigo`);

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `empresa_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`numero`);

--
-- Filtros para la tabla `evaluacion`
--
ALTER TABLE `evaluacion`
  ADD CONSTRAINT `evaluacion_ibfk_1` FOREIGN KEY (`curso`) REFERENCES `curso` (`codigo`);

--
-- Filtros para la tabla `examenes_aplicados`
--
ALTER TABLE `examenes_aplicados`
  ADD CONSTRAINT `examenes_aplicados_ibfk_1` FOREIGN KEY (`prospecto`) REFERENCES `prospecto` (`numero`),
  ADD CONSTRAINT `examenes_aplicados_ibfk_2` FOREIGN KEY (`evaluacion`) REFERENCES `evaluacion` (`numero`);

--
-- Filtros para la tabla `leccion`
--
ALTER TABLE `leccion`
  ADD CONSTRAINT `leccion_ibfk_1` FOREIGN KEY (`curso`) REFERENCES `curso` (`codigo`);

--
-- Filtros para la tabla `membresia`
--
ALTER TABLE `membresia`
  ADD CONSTRAINT `membresia_ibfk_1` FOREIGN KEY (`empresa`) REFERENCES `empresa` (`numero`),
  ADD CONSTRAINT `membresia_ibfk_2` FOREIGN KEY (`plan_suscripcion`) REFERENCES `plan_suscripcion` (`codigo`);

--
-- Filtros para la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD CONSTRAINT `pregunta_ibfk_1` FOREIGN KEY (`evaluacion`) REFERENCES `evaluacion` (`numero`);

--
-- Filtros para la tabla `prospecto`
--
ALTER TABLE `prospecto`
  ADD CONSTRAINT `prospecto_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`numero`);

--
-- Filtros para la tabla `requerimiento`
--
ALTER TABLE `requerimiento`
  ADD CONSTRAINT `requerimiento_ibfk_1` FOREIGN KEY (`vacante`) REFERENCES `vacante` (`numero`);

--
-- Filtros para la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD CONSTRAINT `respuesta_ibfk_1` FOREIGN KEY (`pregunta`) REFERENCES `pregunta` (`numero`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`codigo`);

--
-- Filtros para la tabla `vacante`
--
ALTER TABLE `vacante`
  ADD CONSTRAINT `vacante_ibfk_1` FOREIGN KEY (`tipo_contrato`) REFERENCES `tipo_contrato` (`codigo`),
  ADD CONSTRAINT `vacante_ibfk_2` FOREIGN KEY (`empresa`) REFERENCES `empresa` (`numero`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
