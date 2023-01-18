-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-01-2023 a las 23:50:40
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `charpierre`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuarios_login` (IN `usuario` VARCHAR(50), IN `pass` VARCHAR(50))   begin
	SET @id =(SELECT  id_usuario as id
				FROM usuarios WHERE usuario=usuario and contrasena= md5(pass) and estatus='ACTIVO');

	SELECT  md5(id_usuario) as id,
			usuario,
			concat(nombres,' ', ap_paterno,' ', ap_materno) as nombre,
			foto_usuario as foto
	 FROM usuarios WHERE usuario=usuario and contrasena= md5(pass) and estatus='ACTIVO';
     
     
                
	UPDATE `usuarios` SET `fecha_ultimoAcceso` = NOW() WHERE (`id_usuario` = @id);
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `ruta` varchar(50) NOT NULL,
  `Icono` varchar(50) NOT NULL,
  `id_modulo_padre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulo`, `nombre`, `ruta`, `Icono`, `id_modulo_padre`) VALUES
(1, 'HOME', 'home', 'home', 0),
(2, 'SERVICIOS', 'servicios', 'server', 0),
(3, 'PORTAFOLIO', 'portafolio', 'briefcase', 0),
(4, 'NOSOTROS', 'nosotros', 'address-card', 0),
(5, 'CONTACTO', 'contacto', 'phone', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `id_perfil` bigint(20) NOT NULL,
  `nombre_perfil` varchar(30) NOT NULL,
  `estatus` varchar(10) NOT NULL,
  `usuario_registro` bigint(20) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `usuario_movimiento` bigint(20) NOT NULL,
  `fecha_movimiento` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id_perfil`, `nombre_perfil`, `estatus`, `usuario_registro`, `fecha_registro`, `usuario_movimiento`, `fecha_movimiento`) VALUES
(1, 'ADMINISTRADOR', 'ACTIVO', 0, '2022-07-24 20:35:00', 0, '2022-07-24 20:35:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso_modulo_perfil`
--

CREATE TABLE `permiso_modulo_perfil` (
  `id_permiso` bigint(20) DEFAULT NULL,
  `id_perfil` bigint(20) DEFAULT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `usuario_registro` bigint(20) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `usuario_movimiento` bigint(20) NOT NULL,
  `fecha_movimiento` datetime NOT NULL,
  `fecha_ultimoAcceso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` bigint(20) NOT NULL,
  `usuario` varchar(15) NOT NULL,
  `nombres` varchar(60) NOT NULL,
  `ap_paterno` varchar(40) NOT NULL,
  `ap_materno` varchar(40) NOT NULL,
  `contrasena` varchar(50) NOT NULL,
  `foto_usuario` varchar(50) NOT NULL,
  `id_perfil` bigint(20) NOT NULL,
  `estatus` varchar(10) NOT NULL,
  `usuario_registro` bigint(20) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `usuario_movimiento` bigint(20) NOT NULL,
  `fecha_movimiento` datetime NOT NULL,
  `fecha_ultimoAcceso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `nombres`, `ap_paterno`, `ap_materno`, `contrasena`, `foto_usuario`, `id_perfil`, `estatus`, `usuario_registro`, `fecha_registro`, `usuario_movimiento`, `fecha_movimiento`, `fecha_ultimoAcceso`) VALUES
(1, 'carlos', 'CARLOS NAZARETH', 'GOMEZ', 'PEREZ', 'dc599a9972fde3045dab59dbd1ae170b', 'avatar.png', 1, 'ACTIVO', 0, '2022-07-24 20:37:12', 0, '2022-07-24 20:37:12', '2023-01-18 16:20:08');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulo`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id_perfil`);

--
-- Indices de la tabla `permiso_modulo_perfil`
--
ALTER TABLE `permiso_modulo_perfil`
  ADD KEY `id_perfil` (`id_perfil`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_perfil` (`id_perfil`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id_perfil` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `permiso_modulo_perfil`
--
ALTER TABLE `permiso_modulo_perfil`
  ADD CONSTRAINT `permiso_modulo_perfil_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`),
  ADD CONSTRAINT `permiso_modulo_perfil_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_modulo`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
