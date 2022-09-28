/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 100131
Source Host           : localhost:3306
Source Database       : u_proyecto02

Target Server Type    : MYSQL
Target Server Version : 100131
File Encoding         : 65001

Date: 2019-05-02 11:15:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for generos
-- ----------------------------
DROP TABLE IF EXISTS `generos`;
CREATE TABLE `generos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `genero` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of generos
-- ----------------------------
INSERT INTO `generos` VALUES ('1', 'Disparos');
INSERT INTO `generos` VALUES ('2', 'Acción');
INSERT INTO `generos` VALUES ('4', 'RPG');
INSERT INTO `generos` VALUES ('5', 'Aventura');
INSERT INTO `generos` VALUES ('6', 'MMO');
INSERT INTO `generos` VALUES ('7', 'Carreras');
INSERT INTO `generos` VALUES ('8', 'Peleas');
INSERT INTO `generos` VALUES ('9', 'VR');
INSERT INTO `generos` VALUES ('10', 'Niños');
INSERT INTO `generos` VALUES ('11', 'Horror');

-- ----------------------------
-- Table structure for plataformas
-- ----------------------------
DROP TABLE IF EXISTS `plataformas`;
CREATE TABLE `plataformas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `plataforma` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of plataformas
-- ----------------------------
INSERT INTO `plataformas` VALUES ('1', 'PS3');
INSERT INTO `plataformas` VALUES ('2', 'PS4');
INSERT INTO `plataformas` VALUES ('3', 'Xbox 360');
INSERT INTO `plataformas` VALUES ('4', 'Xbox One');
INSERT INTO `plataformas` VALUES ('5', 'Wii');
INSERT INTO `plataformas` VALUES ('6', 'Wii U');
INSERT INTO `plataformas` VALUES ('7', 'PC');
INSERT INTO `plataformas` VALUES ('8', 'Nintendo 64');
INSERT INTO `plataformas` VALUES ('9', 'Atari');
INSERT INTO `plataformas` VALUES ('10', 'PSP');
INSERT INTO `plataformas` VALUES ('11', 'PSP Vita');
INSERT INTO `plataformas` VALUES ('12', 'Súper Nintendo');

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `navbar_color` varchar(20) DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  `actualizado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES ('5', 'Roberto Orozco', 'jslocal@localhost.com', '$2y$10$DccaS5qBuQ24Q5NHe29KceN3/8RrA/./UdYYwVQnPSUOS6QTxy48K', null, '2019-01-14 19:03:03', '2019-01-14 12:03:03');
INSERT INTO `usuarios` VALUES ('6', 'John Doe', 'pruebas@pruebas.com', '$2y$10$x2xzkZrk13ln0WKbg5eBL.xtb5rtzDkyD4bhfCVgBQaNrjdqszsoG', null, '2019-01-14 19:04:34', '2019-01-20 13:49:22');
INSERT INTO `usuarios` VALUES ('7', 'Pedrito', 'pedrito@email.com', '123', null, '2019-01-20 13:49:40', '2019-01-20 13:49:42');
INSERT INTO `usuarios` VALUES ('8', 'Juanito', 'juanito@email.com', '123', null, '2019-01-20 13:49:50', '2019-01-20 13:49:51');
INSERT INTO `usuarios` VALUES ('9', 'admin', 'jslocal1@localhost.com', '$2y$10$fYDLm18HuNPMXB/xETHxLeTJnGRHaqBfAIDluUZ4yXKqKZ4kZro2.', null, '2019-04-26 23:23:54', '2019-04-26 16:23:54');

-- ----------------------------
-- Table structure for videojuegos
-- ----------------------------
DROP TABLE IF EXISTS `videojuegos`;
CREATE TABLE `videojuegos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `portada` varchar(255) DEFAULT NULL,
  `id_genero` int(10) DEFAULT NULL,
  `id_plataforma` int(10) DEFAULT NULL,
  `calificacion` int(10) DEFAULT NULL,
  `opinion` text,
  `creado` datetime DEFAULT NULL,
  `actualizado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of videojuegos
-- ----------------------------
INSERT INTO `videojuegos` VALUES ('1', '5', 'Bioshock', '44490310_78544275.jpg', '1', '3', '3', 'Lorem ipsum', '2019-01-14 23:11:00', '2019-01-15 16:48:45');
INSERT INTO `videojuegos` VALUES ('3', '5', 'Assassin\'s Creed III', '99746908_22854904.jpg', '2', '3', '5', 'Bastante interesante su historia, me agrado mucho más que Brotherhood.', '2019-01-15 18:17:34', '2019-02-08 14:21:24');
INSERT INTO `videojuegos` VALUES ('5', '5', 'Killzone 2', '68478550_89866099.jpg', '1', '1', '5', 'Hola mundo de nuevo!', '2019-01-15 18:36:19', '2019-02-08 14:21:50');
INSERT INTO `videojuegos` VALUES ('6', '5', 'Deus Ex Human Revolution', '58372173_89352869.jpg', '4', '7', '5', 'Lorem ipsum dolorem atem', '2019-01-15 19:22:16', '2019-01-16 12:05:31');
INSERT INTO `videojuegos` VALUES ('7', '6', 'Batman', 'batman.jpg', '1', '4', '5', 'Lorem ipsum dolorem', '2019-01-20 13:50:22', '2019-01-20 13:50:23');
INSERT INTO `videojuegos` VALUES ('8', '7', 'Black Ops III', 'blackops3.jpg', '1', '4', '4', 'Lorem', '2019-01-20 13:50:55', '2019-01-20 14:11:23');
INSERT INTO `videojuegos` VALUES ('9', '8', 'Call of Duty Ghosts', 'codghosts.jpg', '1', '4', '3', 'Lorem ipsum', '2019-01-20 13:50:55', '2019-01-20 14:12:00');
INSERT INTO `videojuegos` VALUES ('10', '7', 'GTA V', 'gta5.jpg', '2', '6', '5', 'Lorem ipsum', '2019-01-20 13:50:55', '2019-01-20 13:53:02');
INSERT INTO `videojuegos` VALUES ('11', '6', 'Justice League', 'justiceleague.jpg', '5', '3', '4', 'Lorem ipsum', '2019-01-20 13:50:55', '2019-01-20 13:53:54');
INSERT INTO `videojuegos` VALUES ('12', '8', 'Last of Us', 'lastofus.jpg', '4', '5', '5', 'Lorem ipsum dolorem', '2019-01-20 13:50:55', '2019-01-20 13:53:02');
INSERT INTO `videojuegos` VALUES ('13', '6', 'Mass Effect', 'masseffect.jpg', '5', '3', '5', 'Lorem ipsum dolorem', '2019-01-20 13:50:55', '2019-01-20 13:53:02');
INSERT INTO `videojuegos` VALUES ('14', '7', 'Spiderman', 'spiderman.jpg', '6', '3', '4', 'Lorem ipsum', '2019-01-20 13:50:55', '2019-01-20 13:53:02');
INSERT INTO `videojuegos` VALUES ('15', '5', 'Battlefield 3', '34463996_42445248.jpg', '1', '1', '5', 'Nuestra opinión del juego.', '2019-03-22 18:28:49', '2019-03-22 11:28:49');
INSERT INTO `videojuegos` VALUES ('16', '9', 'Prueba de videojuego editado', '96359231_73565604.png', '10', '8', '5', 'Hola mundo de nuevo!', '2019-04-26 23:25:17', '2019-04-26 16:26:30');
