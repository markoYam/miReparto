/*
 Navicat Premium Data Transfer

 Source Server         : Local_phpMyAdmin
 Source Server Type    : MySQL
 Source Server Version : 100424
 Source Host           : localhost:3306
 Source Schema         : mireparto

 Target Server Type    : MySQL
 Target Server Version : 100424
 File Encoding         : 65001

 Date: 30/03/2023 13:49:07
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbl_estatus
-- ----------------------------
DROP TABLE IF EXISTS `tbl_estatus`;
CREATE TABLE `tbl_estatus`  (
  `idEstatus` bigint NOT NULL AUTO_INCREMENT,
  `nbEstatus` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Estatus',
  `nbModulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Modulo al que pertenece',
  PRIMARY KEY (`idEstatus`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_estatus
-- ----------------------------
INSERT INTO `tbl_estatus` VALUES (1, 'ACTIVO', 'GENERAL');
INSERT INTO `tbl_estatus` VALUES (2, 'INACTIVO', 'GENERAL');
INSERT INTO `tbl_estatus` VALUES (3, 'CANCELADO', 'GENERAL');
INSERT INTO `tbl_estatus` VALUES (4, 'GENERADO', 'RUTA');
INSERT INTO `tbl_estatus` VALUES (5, 'EN PROCESO', 'RUTA');
INSERT INTO `tbl_estatus` VALUES (6, 'CANCELADO', 'RUTA');
INSERT INTO `tbl_estatus` VALUES (7, 'FINALIZADO', 'RUTA');
INSERT INTO `tbl_estatus` VALUES (8, 'GENERADO', 'PARADAS');
INSERT INTO `tbl_estatus` VALUES (9, 'ENTREGADO', 'PARADAS');
INSERT INTO `tbl_estatus` VALUES (10, 'CANCELADO/NO ENTREGADO', 'PARADAS');

-- ----------------------------
-- Table structure for tbl_paradas
-- ----------------------------
DROP TABLE IF EXISTS `tbl_paradas`;
CREATE TABLE `tbl_paradas`  (
  `idParada` int NOT NULL AUTO_INCREMENT,
  `idRuta` int NOT NULL,
  `fecha` date NOT NULL,
  `idEstatus` int NOT NULL,
  `latitud` decimal(10, 8) NOT NULL,
  `longitud` decimal(11, 8) NOT NULL,
  `comentarios` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `cliente` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `feActualizacion` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`idParada`) USING BTREE,
  INDEX `idRuta`(`idRuta`) USING BTREE,
  CONSTRAINT `tbl_paradas_ibfk_1` FOREIGN KEY (`idRuta`) REFERENCES `tbl_ruteo` (`idRuteo`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_paradas
-- ----------------------------
INSERT INTO `tbl_paradas` VALUES (1, 2, '2023-03-28', 8, 20.20905200, -89.29227700, '48 y 47 y 24', 'Marco Yam', '2023-03-30 18:03:44');
INSERT INTO `tbl_paradas` VALUES (2, 2, '2023-03-28', 3, 20.20842800, -89.28950900, '45 y 93 y 23', 'Marco Yam', '2023-03-30 06:26:24');
INSERT INTO `tbl_paradas` VALUES (3, 2, '2023-03-28', 2, 20.20621300, -89.28116200, '23 y 49 y 23', 'Marco Yam', '2023-03-30 06:26:07');
INSERT INTO `tbl_paradas` VALUES (4, 2, '2023-03-28', 8, 20.20288000, -89.28486900, '12 y 34 52', 'Marco Yam', NULL);
INSERT INTO `tbl_paradas` VALUES (8, 2, '2023-03-28', 8, 20.20641340, -89.28481980, '49 y 23 y 12', 'Marco', NULL);

-- ----------------------------
-- Table structure for tbl_productos
-- ----------------------------
DROP TABLE IF EXISTS `tbl_productos`;
CREATE TABLE `tbl_productos`  (
  `idProducto` bigint NOT NULL AUTO_INCREMENT,
  `nbProducto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `desProducto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dcPrecioCompra` float NOT NULL,
  `dcPrecioVenta` float NOT NULL,
  `dcComision` float NOT NULL,
  PRIMARY KEY (`idProducto`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_productos
-- ----------------------------
INSERT INTO `tbl_productos` VALUES (1, 'Toallas para mano', 'Toallas para mano', 40, 65, 5);

-- ----------------------------
-- Table structure for tbl_productosparadas
-- ----------------------------
DROP TABLE IF EXISTS `tbl_productosparadas`;
CREATE TABLE `tbl_productosparadas`  (
  `idProductoParada` bigint NOT NULL AUTO_INCREMENT,
  `idParada` bigint NOT NULL,
  `idProducto` bigint NOT NULL,
  `dcCantidad` float NOT NULL,
  PRIMARY KEY (`idProductoParada`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_productosparadas
-- ----------------------------
INSERT INTO `tbl_productosparadas` VALUES (1, 1, 1, 2);
INSERT INTO `tbl_productosparadas` VALUES (2, 2, 1, 5);

-- ----------------------------
-- Table structure for tbl_repartidores
-- ----------------------------
DROP TABLE IF EXISTS `tbl_repartidores`;
CREATE TABLE `tbl_repartidores`  (
  `idRepartidor` bigint NOT NULL AUTO_INCREMENT,
  `nbRepartidor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`idRepartidor`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_repartidores
-- ----------------------------
INSERT INTO `tbl_repartidores` VALUES (1, 'Hector Yam');
INSERT INTO `tbl_repartidores` VALUES (2, 'Marco Yam');
INSERT INTO `tbl_repartidores` VALUES (3, 'Martha Camargo');

-- ----------------------------
-- Table structure for tbl_ruteo
-- ----------------------------
DROP TABLE IF EXISTS `tbl_ruteo`;
CREATE TABLE `tbl_ruteo`  (
  `idRuteo` int NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `Folio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Folio de la ruta',
  `idRepartidor` int NOT NULL,
  `idEstatus` bigint NOT NULL COMMENT 'Validar si la ruta esta activa o no',
  `feFin` datetime(0) NULL DEFAULT NULL COMMENT 'Fecha en que finalizo la ruta',
  PRIMARY KEY (`idRuteo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_ruteo
-- ----------------------------
INSERT INTO `tbl_ruteo` VALUES (2, '2023-03-31', 'RUTA 1', 1, 4, NULL);
INSERT INTO `tbl_ruteo` VALUES (3, '2023-03-28', 'RUTA 2', 2, 4, NULL);

-- ----------------------------
-- Table structure for tbl_seguimientogps
-- ----------------------------
DROP TABLE IF EXISTS `tbl_seguimientogps`;
CREATE TABLE `tbl_seguimientogps`  (
  `idLocalizacion` bigint NOT NULL AUTO_INCREMENT,
  `idRepartidor` bigint NOT NULL,
  `idRuta` bigint NOT NULL,
  `feRegistro` datetime(0) NOT NULL,
  `latitud` float NOT NULL,
  `longitud` float NOT NULL,
  PRIMARY KEY (`idLocalizacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_seguimientogps
-- ----------------------------
INSERT INTO `tbl_seguimientogps` VALUES (1, 1, 0, '2023-03-30 06:49:59', 20.2059, -89.2851);
INSERT INTO `tbl_seguimientogps` VALUES (2, 1, 0, '2023-03-30 06:50:00', 20.2059, -89.2851);
INSERT INTO `tbl_seguimientogps` VALUES (3, 1, 0, '2023-03-30 06:50:01', 20.2059, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (4, 1, 0, '2023-03-30 06:50:03', 20.2059, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (5, 1, 0, '2023-03-30 06:50:04', 20.206, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (6, 1, 0, '2023-03-30 06:50:05', 20.206, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (7, 1, 0, '2023-03-30 06:50:06', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (8, 1, 0, '2023-03-30 06:50:07', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (9, 1, 0, '2023-03-30 06:50:08', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (10, 1, 0, '2023-03-30 06:50:09', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (11, 1, 0, '2023-03-30 06:50:10', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (12, 1, 0, '2023-03-30 06:50:11', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (13, 1, 0, '2023-03-30 06:50:12', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (14, 1, 0, '2023-03-30 06:50:13', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (15, 1, 0, '2023-03-30 06:50:14', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (16, 1, 0, '2023-03-30 06:50:15', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (17, 1, 0, '2023-03-30 06:50:16', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (18, 1, 0, '2023-03-30 06:50:17', 20.206, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (19, 1, 0, '2023-03-30 06:50:18', 20.206, -89.2853);
INSERT INTO `tbl_seguimientogps` VALUES (20, 1, 0, '2023-03-30 06:50:19', 20.206, -89.2853);
INSERT INTO `tbl_seguimientogps` VALUES (21, 1, 0, '2023-03-30 06:50:20', 20.206, -89.2854);
INSERT INTO `tbl_seguimientogps` VALUES (22, 1, 0, '2023-03-30 06:50:21', 20.206, -89.2854);
INSERT INTO `tbl_seguimientogps` VALUES (23, 1, 0, '2023-03-30 06:50:22', 20.206, -89.2854);
INSERT INTO `tbl_seguimientogps` VALUES (24, 1, 0, '2023-03-30 06:50:23', 20.206, -89.2854);
INSERT INTO `tbl_seguimientogps` VALUES (25, 1, 0, '2023-03-30 06:50:24', 20.206, -89.2853);
INSERT INTO `tbl_seguimientogps` VALUES (26, 1, 0, '2023-03-30 06:50:25', 20.206, -89.2853);
INSERT INTO `tbl_seguimientogps` VALUES (27, 1, 0, '2023-03-30 06:50:26', 20.2061, -89.2853);
INSERT INTO `tbl_seguimientogps` VALUES (28, 1, 0, '2023-03-30 06:50:27', 20.2061, -89.2853);
INSERT INTO `tbl_seguimientogps` VALUES (29, 1, 0, '2023-03-30 06:50:28', 20.2061, -89.2853);
INSERT INTO `tbl_seguimientogps` VALUES (30, 1, 0, '2023-03-30 06:50:29', 20.2062, -89.2853);
INSERT INTO `tbl_seguimientogps` VALUES (31, 1, 0, '2023-03-30 06:50:30', 20.2062, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (32, 1, 0, '2023-03-30 06:50:31', 20.2062, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (33, 1, 0, '2023-03-30 06:50:32', 20.2062, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (34, 1, 0, '2023-03-30 06:50:33', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (35, 1, 0, '2023-03-30 06:50:34', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (36, 1, 0, '2023-03-30 06:50:35', 20.2061, -89.2853);
INSERT INTO `tbl_seguimientogps` VALUES (37, 1, 0, '2023-03-30 06:50:36', 20.206, -89.2853);
INSERT INTO `tbl_seguimientogps` VALUES (38, 1, 0, '2023-03-30 06:50:37', 20.206, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (39, 1, 0, '2023-03-30 06:50:38', 20.206, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (40, 1, 0, '2023-03-30 06:50:39', 20.206, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (41, 1, 0, '2023-03-30 06:50:40', 20.2061, -89.2852);
INSERT INTO `tbl_seguimientogps` VALUES (42, 1, 0, '2023-03-30 06:50:41', 20.2061, -89.2852);

-- ----------------------------
-- View structure for paradas_view
-- ----------------------------
DROP VIEW IF EXISTS `paradas_view`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `paradas_view` AS SELECT
	tbl_estatus.idEstatus, 
	tbl_estatus.nbEstatus, 
	tbl_paradas.idParada, 
	tbl_paradas.idRuta, 
	tbl_paradas.fecha, 
	tbl_paradas.latitud, 
	tbl_paradas.longitud
FROM
	tbl_paradas INNER JOIN tbl_estatus ON tbl_estatus.idEstatus = tbl_paradas.idEstatus ;

-- ----------------------------
-- View structure for productos_parada_view
-- ----------------------------
DROP VIEW IF EXISTS `productos_parada_view`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `productos_parada_view` AS SELECT
	producto.idProducto, 
	producto.nbProducto, 
	producto.desProducto, 
	producto.dcPrecioCompra, 
	producto.dcPrecioVenta, 
	producto.dcComision, 	 
	productoParada.dcCantidad, 
	productoParada.idParada,
	parada.idRuta,
	tbl_estatus.idEstatus,
	tbl_estatus.nbEstatus
FROM
	tbl_productos producto 
	INNER JOIN tbl_productosparadas productoParada on producto.idProducto = productoParada.idProducto
	INNER JOIN tbl_paradas parada on productoParada.idParada = parada.idParada
	INNER JOIN tbl_estatus ON tbl_estatus.idEstatus = parada.idEstatus ;

-- ----------------------------
-- View structure for rutas_view
-- ----------------------------
DROP VIEW IF EXISTS `rutas_view`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `rutas_view` AS SELECT
	tbl_ruteo.idRuteo,
	tbl_ruteo.fecha,
	tbl_ruteo.feFin,
	tbl_ruteo.Folio,
	tbl_ruteo.idRepartidor,
	tbl_repartidores.nbRepartidor,
	tbl_estatus.idEstatus,
	tbl_estatus.nbEstatus
	
FROM
	tbl_ruteo
	INNER JOIN tbl_estatus ON tbl_ruteo.idEstatus = tbl_estatus.idEstatus
	INNER JOIN tbl_repartidores ON tbl_ruteo.idRepartidor = tbl_repartidores.idRepartidor ;

-- ----------------------------
-- Procedure structure for sp_getRutasActivasByRepartidor
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_getRutasActivasByRepartidor`;
delimiter ;;
CREATE PROCEDURE `sp_getRutasActivasByRepartidor`(IN idRepartidor bigint)
BEGIN
	SELECT * FROM rutas_view WHERE rutas_view.idRepartidor = idRepartidor;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
