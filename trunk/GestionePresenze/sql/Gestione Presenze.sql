-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.36-community


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema gestione_presenze
--

CREATE DATABASE IF NOT EXISTS gestione_presenze;
USE gestione_presenze;

--
-- Definition of table `causali`
--

DROP TABLE IF EXISTS `causali`;
CREATE TABLE `causali` (
  `id_motivo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `descrizione` varchar(4000) NOT NULL,
  PRIMARY KEY (`id_motivo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Contiene tutti i vari motivi di assenza che l''azienda preved';

--
-- Dumping data for table `causali`
--

/*!40000 ALTER TABLE `causali` DISABLE KEYS */;
INSERT INTO `causali` (`id_motivo`,`nome`,`descrizione`) VALUES 
 (1,'MALATTIA','Quando un dipendente è malato'),
 (2,'CONGEDO','In caso di matrimoni, funerali, traslochi, ecc...'),
 (3,'SCUOLA','In caso di presenza scolastica del dipendente'),
 (4,'VACANZA','In caso di vacanza del dipendente'),
 (5,'MEDICO','In caso di mezza giornata di vacanza del dipendente'),
 (6,'FUORI SEDE','In caso di presenza fuori sede');
/*!40000 ALTER TABLE `causali` ENABLE KEYS */;


--
-- Definition of table `dipendenti`
--

DROP TABLE IF EXISTS `dipendenti`;
CREATE TABLE `dipendenti` (
  `id_dipendente` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `cognome` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(4500) DEFAULT NULL COMMENT 'criptata md5',
  `fk_filiale` int(10) unsigned NOT NULL COMMENT 'id della filiale di appartenenza del dipendente',
  `email` varchar(45) NOT NULL,
  `stato_att` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '1-> disponibile 2-> occupato 3->non al PC',
  `commento_stato` varchar(35) DEFAULT NULL,
  `telefono` int(10) unsigned NOT NULL,
  `natel` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_dipendente`),
  UNIQUE KEY `unique_username` (`username`),
  KEY `FK_dipendenti_1` (`fk_filiale`),
  CONSTRAINT `FK_dipendenti_1` FOREIGN KEY (`fk_filiale`) REFERENCES `filiali` (`id_filiale`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1 COMMENT='Contiene tutti i dati che riguardano i dipendenti';

--
-- Dumping data for table `dipendenti`
--

/*!40000 ALTER TABLE `dipendenti` DISABLE KEYS */;
INSERT INTO `dipendenti` (`id_dipendente`,`nome`,`cognome`,`username`,`password`,`fk_filiale`,`email`,`stato_att`,`commento_stato`,`telefono`,`natel`) VALUES 
 (10,'admin','admin','admin','21232f297a57a5a743894a0e4a801fc3',1,'admin@gmail.com',1,'',11,2),
 (11,'user1','g1','u1g1','e10adc3949ba59abbe56e057f20f883e',1,'u1g1@gmail.com',1,'',0,0),
 (12,'user2','g1','u2g1','c4ca4238a0b923820dcc509a6f75849b',1,'u2g1@gmail.com',2,'',0,0),
 (14,'user3','g1','u3g1','7df656af4efecd9b1f69f708e6903b78',1,'u3g1@gmail.com',2,'',0,0),
 (15,'user1','g2','u1g2','7df656af4efecd9b1f69f708e6903b78',1,'u1g2@gmail.com',1,'',0,0),
 (16,'user2','g2','u2g2','7df656af4efecd9b1f69f708e6903b78',1,'u2g2@gmail.com',2,'',0,0),
 (17,'user3','g2','u3g2','7df656af4efecd9b1f69f708e6903b78',1,'u3g2@gmail.com',1,'',0,0),
 (18,'utente','libero','utente','e10adc3949ba59abbe56e057f20f883e',1,'utente@gmail.com',2,'',0,0),
 (19,'test1','test2','test12','7df656af4efecd9b1f69f708e6903b78',1,'te@ie.ci',1,'',0,0),
 (20,'rrgegt','tgergt','trrtg','7df656af4efecd9b1f69f708e6903b78',1,'gt@fh.tj',2,'',0,0),
 (21,'iolo','lolio','oliol','7df656af4efecd9b1f69f708e6903b78',1,'ii@tt.tt',1,'',0,0);
INSERT INTO `dipendenti` (`id_dipendente`,`nome`,`cognome`,`username`,`password`,`fk_filiale`,`email`,`stato_att`,`commento_stato`,`telefono`,`natel`) VALUES 
 (22,'prova','prova','prova','7df656af4efecd9b1f69f708e6903b78',1,'prova@a.aa',1,NULL,0,0),
 (23,'Ethan','Lo Sbocchino','sboc','7df656af4efecd9b1f69f708e6903b78',2,'ethan.losbocchino@abc.com',1,NULL,0,0),
 (24,'aaa','aaa','aaa','7df656af4efecd9b1f69f708e6903b78',1,'aaa@aaa.com',1,NULL,111,1111);
/*!40000 ALTER TABLE `dipendenti` ENABLE KEYS */;


--
-- Definition of table `dipendenti_gruppi`
--

DROP TABLE IF EXISTS `dipendenti_gruppi`;
CREATE TABLE `dipendenti_gruppi` (
  `fk_dipendente` int(10) unsigned NOT NULL,
  `fk_gruppo` int(11) NOT NULL,
  PRIMARY KEY (`fk_dipendente`,`fk_gruppo`),
  KEY `fk_dipendenti_has_gruppi_dipendenti1` (`fk_dipendente`),
  KEY `fk_dipendenti_has_gruppi_gruppi1` (`fk_gruppo`),
  CONSTRAINT `fk_dipendenti_has_gruppi_dipendenti1` FOREIGN KEY (`fk_dipendente`) REFERENCES `dipendenti` (`id_dipendente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dipendenti_has_gruppi_gruppi1` FOREIGN KEY (`fk_gruppo`) REFERENCES `gruppi` (`id_gruppo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dipendenti_gruppi`
--

/*!40000 ALTER TABLE `dipendenti_gruppi` DISABLE KEYS */;
INSERT INTO `dipendenti_gruppi` (`fk_dipendente`,`fk_gruppo`) VALUES 
 (10,1),
 (11,15),
 (12,15),
 (14,15),
 (15,16),
 (16,16),
 (17,16),
 (18,18),
 (23,15);
/*!40000 ALTER TABLE `dipendenti_gruppi` ENABLE KEYS */;


--
-- Definition of table `eventi`
--

DROP TABLE IF EXISTS `eventi`;
CREATE TABLE `eventi` (
  `id_evento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data_da` varchar(45) NOT NULL,
  `data_a` varchar(45) NOT NULL,
  `priorita` int(10) unsigned NOT NULL,
  `commento` varchar(450) NOT NULL,
  `fk_dipendente` int(10) unsigned NOT NULL,
  `fk_causale` int(10) unsigned NOT NULL,
  `stato` varchar(45) NOT NULL DEFAULT '1' COMMENT '1->richiesto 2->accettato 3->segnalato',
  `commento_segnalazione` varchar(45) DEFAULT NULL,
  `durata` varchar(1) NOT NULL DEFAULT 'G',
  PRIMARY KEY (`id_evento`),
  KEY `FK_event_1` (`fk_dipendente`),
  KEY `FK_event_2` (`fk_causale`),
  CONSTRAINT `FK_event_1` FOREIGN KEY (`fk_dipendente`) REFERENCES `dipendenti` (`id_dipendente`),
  CONSTRAINT `FK_event_2` FOREIGN KEY (`fk_causale`) REFERENCES `causali` (`id_motivo`)
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eventi`
--

/*!40000 ALTER TABLE `eventi` DISABLE KEYS */;
/*!40000 ALTER TABLE `eventi` ENABLE KEYS */;


--
-- Definition of table `festivi`
--

DROP TABLE IF EXISTS `festivi`;
CREATE TABLE `festivi` (
  `id_festivo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `data` varchar(45) DEFAULT NULL COMMENT 'data di cadenza del festivo',
  `durata` varchar(1) NOT NULL DEFAULT 'G',
  `ricorsivo` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id_festivo`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1 COMMENT='Contiene tutti i giorni festivi che l''azienda riconosce';

--
-- Dumping data for table `festivi`
--

/*!40000 ALTER TABLE `festivi` DISABLE KEYS */;
INSERT INTO `festivi` (`id_festivo`,`nome`,`data`,`durata`,`ricorsivo`) VALUES 
 (43,'MartedÃ¬ grasso','1266274800','P',0),
 (58,'San Giuseppe','1268953200','G',1),
 (61,'asdM','1269385200','M',0),
 (62,'asdP','1269558000','P',0);
/*!40000 ALTER TABLE `festivi` ENABLE KEYS */;


--
-- Definition of table `festivi_effettuati`
--

DROP TABLE IF EXISTS `festivi_effettuati`;
CREATE TABLE `festivi_effettuati` (
  `fk_filiale` int(10) unsigned NOT NULL,
  `fk_festivo` int(10) unsigned NOT NULL,
  PRIMARY KEY (`fk_filiale`,`fk_festivo`),
  KEY `FK_festivi_effettuati_2` (`fk_festivo`),
  CONSTRAINT `FK_festivi_effettuati_1` FOREIGN KEY (`fk_filiale`) REFERENCES `filiali` (`id_filiale`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_festivi_effettuati_2` FOREIGN KEY (`fk_festivo`) REFERENCES `festivi` (`id_festivo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Contiene i festivi effettuati con id della filiale e del fes';

--
-- Dumping data for table `festivi_effettuati`
--

/*!40000 ALTER TABLE `festivi_effettuati` DISABLE KEYS */;
INSERT INTO `festivi_effettuati` (`fk_filiale`,`fk_festivo`) VALUES 
 (1,43),
 (1,58),
 (1,61),
 (1,62);
/*!40000 ALTER TABLE `festivi_effettuati` ENABLE KEYS */;


--
-- Definition of table `filiali`
--

DROP TABLE IF EXISTS `filiali`;
CREATE TABLE `filiali` (
  `id_filiale` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `indirizzo` varchar(45) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `fk_paese` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_filiale`),
  KEY `fk_filiali_paesi1` (`fk_paese`),
  CONSTRAINT `fk_filiali_paesi1` FOREIGN KEY (`fk_paese`) REFERENCES `paesi` (`id_paese`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `filiali`
--

/*!40000 ALTER TABLE `filiali` DISABLE KEYS */;
INSERT INTO `filiali` (`id_filiale`,`nome`,`indirizzo`,`telefono`,`fk_paese`) VALUES 
 (1,'Bellinzona','a','1',15),
 (2,'Lugano','a','a',0),
 (3,'Locarno','','a',0);
/*!40000 ALTER TABLE `filiali` ENABLE KEYS */;


--
-- Definition of table `gruppi`
--

DROP TABLE IF EXISTS `gruppi`;
CREATE TABLE `gruppi` (
  `id_gruppo` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `amministra` varchar(1) NOT NULL DEFAULT 'N' COMMENT 'Indica se il gruppo è di amministrazione e quindi puo vedere tutto nel sistema di gestione presenze',
  PRIMARY KEY (`id_gruppo`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gruppi`
--

/*!40000 ALTER TABLE `gruppi` DISABLE KEYS */;
INSERT INTO `gruppi` (`id_gruppo`,`nome`,`amministra`) VALUES 
 (1,'admin','Y'),
 (15,'gruppo 1','N'),
 (16,'gruppo 2','N'),
 (18,'default','N');
/*!40000 ALTER TABLE `gruppi` ENABLE KEYS */;


--
-- Definition of table `gruppi_pagine`
--

DROP TABLE IF EXISTS `gruppi_pagine`;
CREATE TABLE `gruppi_pagine` (
  `fk_gruppo` int(11) NOT NULL,
  `fk_pagina` int(11) NOT NULL,
  PRIMARY KEY (`fk_gruppo`,`fk_pagina`),
  KEY `fk_gruppi_has_pagine_gruppi1` (`fk_gruppo`),
  KEY `fk_gruppi_has_pagine_pagine1` (`fk_pagina`),
  CONSTRAINT `fk_gruppi_has_pagine_gruppi1` FOREIGN KEY (`fk_gruppo`) REFERENCES `gruppi` (`id_gruppo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_gruppi_has_pagine_pagine1` FOREIGN KEY (`fk_pagina`) REFERENCES `pagine` (`id_pagina`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gruppi_pagine`
--

/*!40000 ALTER TABLE `gruppi_pagine` DISABLE KEYS */;
INSERT INTO `gruppi_pagine` (`fk_gruppo`,`fk_pagina`) VALUES 
 (1,1),
 (1,2),
 (1,3),
 (1,4),
 (15,1),
 (15,3),
 (15,4),
 (16,1),
 (16,4);
/*!40000 ALTER TABLE `gruppi_pagine` ENABLE KEYS */;


--
-- Definition of table `nazioni`
--

DROP TABLE IF EXISTS `nazioni`;
CREATE TABLE `nazioni` (
  `id_nazione` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id_nazione`)
) ENGINE=InnoDB AUTO_INCREMENT=605 DEFAULT CHARSET=latin1 COMMENT='Contiene tutte le nazioni dove c''è una filiale';

--
-- Dumping data for table `nazioni`
--

/*!40000 ALTER TABLE `nazioni` DISABLE KEYS */;
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (8,'Albania\n'),
 (9,'American Samoa\n'),
 (10,'Andorra\n'),
 (11,'Antigua & Barbuda\n'),
 (12,'Argentina\n'),
 (13,'Armenia\n'),
 (14,'Aruba\n'),
 (15,'Australia\n'),
 (16,'Austria\n'),
 (17,'Angola\n'),
 (18,'Azerbaijan\n'),
 (19,'Bahamas\n'),
 (20,'Bahrain\n'),
 (21,'Bangladesh\n'),
 (22,'Barbados\n'),
 (23,'Belarus\n'),
 (24,'Belgium\n'),
 (25,'Belize\n'),
 (26,'Benin\n'),
 (27,'Bermuda\n'),
 (28,'Bhutan\n'),
 (29,'Bosnia\n'),
 (30,'Botswana\n'),
 (31,'Brazil\n'),
 (32,'British Virgin Islan\n'),
 (33,'Brunei\n'),
 (34,'Bulgaria\n'),
 (35,'Burkina Faso\n'),
 (36,'Burundi\n'),
 (37,'Cambodia\n'),
 (38,'Cameroon\n'),
 (39,'Canada\n'),
 (40,'Central African\n'),
 (41,'Cape Verde\n'),
 (42,'Cayman Islands\n'),
 (43,'Central African Rep\n'),
 (44,'Chad\n'),
 (45,'Chile\n'),
 (46,'Colombia\n'),
 (47,'Cook Islands\n'),
 (48,'Costa Rica\n'),
 (49,'Croatia\n'),
 (50,'Cyprus\n'),
 (51,'Czech Republic\n'),
 (52,'Denmark\n'),
 (53,'Djibouti\n'),
 (54,'Dominica\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (55,'Dominican Republic\n'),
 (56,'Ecuador\n'),
 (57,'Eqiatorial Guinea\n'),
 (58,'Egypt\n'),
 (59,'El Salvador\n'),
 (60,'Eritrea\n'),
 (61,'Estonia\n'),
 (62,'Ethiopia\n'),
 (63,'Faeroe Islands\n'),
 (64,'Fed St of Micronesia\n'),
 (65,'Fiji\n'),
 (66,'Finland\n'),
 (67,'France\n'),
 (68,'French Guiana\n'),
 (69,'French Polynesia\n'),
 (70,'Gabon\n'),
 (71,'Gambia\n'),
 (72,'Georgia\n'),
 (73,'Germany\n'),
 (74,'Gibraltar\n'),
 (75,'Greenland\n'),
 (76,'Grenada\n'),
 (77,'Guadeloupe\n'),
 (78,'Guam\n'),
 (79,'Guatemala\n'),
 (80,'Guinea\n'),
 (81,'Guinea-Bissau\n'),
 (82,'Guyana\n'),
 (83,'Haiti\n'),
 (84,'Honduras\n'),
 (85,'Hong Kong\n'),
 (86,'Hungary\n'),
 (87,'Iceland\n'),
 (88,'India\n'),
 (89,'Ireland\n'),
 (90,'Iraq\n'),
 (91,'Israel\n'),
 (92,'Italy\n'),
 (93,'Jamaica\n'),
 (94,'Japan\n'),
 (95,'Jordan\n'),
 (96,'Kazakhstan\n'),
 (97,'Kiribati\n'),
 (98,'Kuwait\n'),
 (99,'Kyrgyzstan\n'),
 (100,'Laos\n'),
 (101,'Latvia\n'),
 (102,'Lebanon\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (103,'Lesotho\n'),
 (104,'Liberia\n'),
 (105,'Libya\n'),
 (106,'Lichtenstein\n'),
 (107,'Lithuania\n'),
 (108,'Luxembourg\n'),
 (109,'Macau\n'),
 (110,'Macedonia\n'),
 (111,'Madagascar\n'),
 (112,'Malawi\n'),
 (113,'Maldives\n'),
 (114,'Mali\n'),
 (115,'Malta\n'),
 (116,'Marshall Islands\n'),
 (117,'Martinique\n'),
 (118,'Mauritius\n'),
 (119,'Mauuritania\n'),
 (120,'Mexico\n'),
 (121,'Moldava\n'),
 (122,'Monaco\n'),
 (123,'Mongolia\n'),
 (124,'Montenegro\n'),
 (125,'Monteserrat\n'),
 (126,'Morocco\n'),
 (127,'Mozambique\n'),
 (128,'Namibia\n'),
 (129,'Nauru\n'),
 (130,'Nepal\n'),
 (131,'Netherlands\n'),
 (132,'Netherlands Antilles\n'),
 (133,'New Caledonia\n'),
 (134,'New Zealand\n'),
 (135,'Nicaragua\n'),
 (136,'Niger\n'),
 (137,'Norfolk Island\n'),
 (138,'Norway\n'),
 (139,'Oman\n'),
 (140,'Pakistan\n'),
 (141,'Palau\n'),
 (142,'Panama\n'),
 (143,'Papua New Guinea\n'),
 (144,'Paraguay\n'),
 (145,'Peoples Rep of China\n'),
 (146,'Peru\n'),
 (147,'Phillipines\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (148,'Poland\n'),
 (149,'Portugal\n'),
 (150,'Puerto Rico\n'),
 (151,'Qatar\n'),
 (152,'Rawanda\n'),
 (153,'Republic of Congo\n'),
 (154,'Republic of Yemen\n'),
 (155,'Reunion\n'),
 (156,'Romania\n'),
 (157,'San Marino\n'),
 (158,'Saudi Arabia\n'),
 (159,'Senegal\n'),
 (160,'Serbia\n'),
 (161,'Seychelles\n'),
 (162,'Sierra Leone\n'),
 (163,'Singapore\n'),
 (164,'Slovakia\n'),
 (165,'Slovenia\n'),
 (166,'Solomon Islands\n'),
 (167,'South Africa\n'),
 (168,'South Korea\n'),
 (169,'Spain\n'),
 (170,'Sri Lanka\n'),
 (171,'St.Barthelemy\n'),
 (172,'St.Kitts & Nevis\n'),
 (173,'St Helena\n'),
 (174,'St.Lucia\n'),
 (175,'St.Vincent&Grenadine\n'),
 (176,'Sudan\n'),
 (177,'Suriname\n'),
 (178,'Swaziland\n'),
 (179,'Sweeden\n'),
 (180,'Switzerland\n'),
 (181,'Taiwan\n'),
 (182,'Tajikistan\n'),
 (183,'Tanzania\n'),
 (184,'Thailand\n'),
 (185,'Togo\n'),
 (186,'Tonga\n'),
 (187,'Trinidad & Tobago\n'),
 (188,'Tunisia\n'),
 (189,'Turkey\n'),
 (190,'Turks & Caicos Isles\n'),
 (191,'Turkmenistam\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (192,'Tuvalu\n'),
 (193,'Ukraine\n'),
 (194,'United Arab Emirates\n'),
 (195,'United Kingdom\n'),
 (196,'United States\n'),
 (197,'Uruguay\n'),
 (198,'US Virgin Islands\n'),
 (199,'Uzbekistan\n'),
 (200,'Vanuatu\n'),
 (201,'Vatican City\n'),
 (202,'Venezuela\n'),
 (203,'Wallis & Futuna Is\n'),
 (204,'Western Samoa\n'),
 (205,'Zambia\n'),
 (206,'United States\n'),
 (207,'Albania\n'),
 (208,'American Samoa\n'),
 (209,'Andorra\n'),
 (210,'Antigua & Barbuda\n'),
 (211,'Argentina\n'),
 (212,'Armenia\n'),
 (213,'Aruba\n'),
 (214,'Australia\n'),
 (215,'Austria\n'),
 (216,'Angola\n'),
 (217,'Azerbaijan\n'),
 (218,'Bahamas\n'),
 (219,'Bahrain\n'),
 (220,'Bangladesh\n'),
 (221,'Barbados\n'),
 (222,'Belarus\n'),
 (223,'Belgium\n'),
 (224,'Belize\n'),
 (225,'Benin\n'),
 (226,'Bermuda\n'),
 (227,'Bhutan\n'),
 (228,'Bosnia\n'),
 (229,'Botswana\n'),
 (230,'Brazil\n'),
 (231,'British Virgin Islan\n'),
 (232,'Brunei\n'),
 (233,'Bulgaria\n'),
 (234,'Burkina Faso\n'),
 (235,'Burundi\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (236,'Cambodia\n'),
 (237,'Cameroon\n'),
 (238,'Canada\n'),
 (239,'Central African\n'),
 (240,'Cape Verde\n'),
 (241,'Cayman Islands\n'),
 (242,'Central African Rep\n'),
 (243,'Chad\n'),
 (244,'Chile\n'),
 (245,'Colombia\n'),
 (246,'Cook Islands\n'),
 (247,'Costa Rica\n'),
 (248,'Croatia\n'),
 (249,'Cyprus\n'),
 (250,'Czech Republic\n'),
 (251,'Denmark\n'),
 (252,'Djibouti\n'),
 (253,'Dominica\n'),
 (254,'Dominican Republic\n'),
 (255,'Ecuador\n'),
 (256,'Eqiatorial Guinea\n'),
 (257,'Egypt\n'),
 (258,'El Salvador\n'),
 (259,'Eritrea\n'),
 (260,'Estonia\n'),
 (261,'Ethiopia\n'),
 (262,'Faeroe Islands\n'),
 (263,'Fed St of Micronesia\n'),
 (264,'Fiji\n'),
 (265,'Finland\n'),
 (266,'France\n'),
 (267,'French Guiana\n'),
 (268,'French Polynesia\n'),
 (269,'Gabon\n'),
 (270,'Gambia\n'),
 (271,'Georgia\n'),
 (272,'Germany\n'),
 (273,'Gibraltar\n'),
 (274,'Greenland\n'),
 (275,'Grenada\n'),
 (276,'Guadeloupe\n'),
 (277,'Guam\n'),
 (278,'Guatemala\n'),
 (279,'Guinea\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (280,'Guinea-Bissau\n'),
 (281,'Guyana\n'),
 (282,'Haiti\n'),
 (283,'Honduras\n'),
 (284,'Hong Kong\n'),
 (285,'Hungary\n'),
 (286,'Iceland\n'),
 (287,'India\n'),
 (288,'Ireland\n'),
 (289,'Iraq\n'),
 (290,'Israel\n'),
 (291,'Italy\n'),
 (292,'Jamaica\n'),
 (293,'Japan\n'),
 (294,'Jordan\n'),
 (295,'Kazakhstan\n'),
 (296,'Kiribati\n'),
 (297,'Kuwait\n'),
 (298,'Kyrgyzstan\n'),
 (299,'Laos\n'),
 (300,'Latvia\n'),
 (301,'Lebanon\n'),
 (302,'Lesotho\n'),
 (303,'Liberia\n'),
 (304,'Libya\n'),
 (305,'Lichtenstein\n'),
 (306,'Lithuania\n'),
 (307,'Luxembourg\n'),
 (308,'Macau\n'),
 (309,'Macedonia\n'),
 (310,'Madagascar\n'),
 (311,'Malawi\n'),
 (312,'Maldives\n'),
 (313,'Mali\n'),
 (314,'Malta\n'),
 (315,'Marshall Islands\n'),
 (316,'Martinique\n'),
 (317,'Mauritius\n'),
 (318,'Mauuritania\n'),
 (319,'Mexico\n'),
 (320,'Moldava\n'),
 (321,'Monaco\n'),
 (322,'Mongolia\n'),
 (323,'Montenegro\n'),
 (324,'Monteserrat\n'),
 (325,'Morocco\n'),
 (326,'Mozambique\n'),
 (327,'Namibia\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (328,'Nauru\n'),
 (329,'Nepal\n'),
 (330,'Netherlands\n'),
 (331,'Netherlands Antilles\n'),
 (332,'New Caledonia\n'),
 (333,'New Zealand\n'),
 (334,'Nicaragua\n'),
 (335,'Niger\n'),
 (336,'Norfolk Island\n'),
 (337,'Norway\n'),
 (338,'Oman\n'),
 (339,'Pakistan\n'),
 (340,'Palau\n'),
 (341,'Panama\n'),
 (342,'Papua New Guinea\n'),
 (343,'Paraguay\n'),
 (344,'Peoples Rep of China\n'),
 (345,'Peru\n'),
 (346,'Phillipines\n'),
 (347,'Poland\n'),
 (348,'Portugal\n'),
 (349,'Puerto Rico\n'),
 (350,'Qatar\n'),
 (351,'Rawanda\n'),
 (352,'Republic of Congo\n'),
 (353,'Republic of Yemen\n'),
 (354,'Reunion\n'),
 (355,'Romania\n'),
 (356,'San Marino\n'),
 (357,'Saudi Arabia\n'),
 (358,'Senegal\n'),
 (359,'Serbia\n'),
 (360,'Seychelles\n'),
 (361,'Sierra Leone\n'),
 (362,'Singapore\n'),
 (363,'Slovakia\n'),
 (364,'Slovenia\n'),
 (365,'Solomon Islands\n'),
 (366,'South Africa\n'),
 (367,'South Korea\n'),
 (368,'Spain\n'),
 (369,'Sri Lanka\n'),
 (370,'St.Barthelemy\n'),
 (371,'St.Kitts & Nevis\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (372,'St Helena\n'),
 (373,'St.Lucia\n'),
 (374,'St.Vincent&Grenadine\n'),
 (375,'Sudan\n'),
 (376,'Suriname\n'),
 (377,'Swaziland\n'),
 (378,'Sweeden\n'),
 (379,'Switzerland\n'),
 (380,'Taiwan\n'),
 (381,'Tajikistan\n'),
 (382,'Tanzania\n'),
 (383,'Thailand\n'),
 (384,'Togo\n'),
 (385,'Tonga\n'),
 (386,'Trinidad & Tobago\n'),
 (387,'Tunisia\n'),
 (388,'Turkey\n'),
 (389,'Turks & Caicos Isles\n'),
 (390,'Turkmenistam\n'),
 (391,'Tuvalu\n'),
 (392,'Ukraine\n'),
 (393,'United Arab Emirates\n'),
 (394,'United Kingdom\n'),
 (395,'United States\n'),
 (396,'Uruguay\n'),
 (397,'US Virgin Islands\n'),
 (398,'Uzbekistan\n'),
 (399,'Vanuatu\n'),
 (400,'Vatican City\n'),
 (401,'Venezuela\n'),
 (402,'Wallis & Futuna Is\n'),
 (403,'Western Samoa\n'),
 (404,'Zambia\n'),
 (405,'United States\n'),
 (406,'Albania\n'),
 (407,'American Samoa\n'),
 (408,'Andorra\n'),
 (409,'Antigua & Barbuda\n'),
 (410,'Argentina\n'),
 (411,'Armenia\n'),
 (412,'Aruba\n'),
 (413,'Australia\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (414,'Austria\n'),
 (415,'Angola\n'),
 (416,'Azerbaijan\n'),
 (417,'Bahamas\n'),
 (418,'Bahrain\n'),
 (419,'Bangladesh\n'),
 (420,'Barbados\n'),
 (421,'Belarus\n'),
 (422,'Belgium\n'),
 (423,'Belize\n'),
 (424,'Benin\n'),
 (425,'Bermuda\n'),
 (426,'Bhutan\n'),
 (427,'Bosnia\n'),
 (428,'Botswana\n'),
 (429,'Brazil\n'),
 (430,'British Virgin Islan\n'),
 (431,'Brunei\n'),
 (432,'Bulgaria\n'),
 (433,'Burkina Faso\n'),
 (434,'Burundi\n'),
 (435,'Cambodia\n'),
 (436,'Cameroon\n'),
 (437,'Canada\n'),
 (438,'Central African\n'),
 (439,'Cape Verde\n'),
 (440,'Cayman Islands\n'),
 (441,'Central African Rep\n'),
 (442,'Chad\n'),
 (443,'Chile\n'),
 (444,'Colombia\n'),
 (445,'Cook Islands\n'),
 (446,'Costa Rica\n'),
 (447,'Cote d\'Ivoire\n'),
 (448,'Croatia\n'),
 (449,'Cyprus\n'),
 (450,'Czech Republic\n'),
 (451,'Denmark\n'),
 (452,'Djibouti\n'),
 (453,'Dominica\n'),
 (454,'Dominican Republic\n'),
 (455,'Ecuador\n'),
 (456,'Eqiatorial Guinea\n'),
 (457,'Egypt\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (458,'El Salvador\n'),
 (459,'Eritrea\n'),
 (460,'Estonia\n'),
 (461,'Ethiopia\n'),
 (462,'Faeroe Islands\n'),
 (463,'Fed St of Micronesia\n'),
 (464,'Fiji\n'),
 (465,'Finland\n'),
 (466,'France\n'),
 (467,'French Guiana\n'),
 (468,'French Polynesia\n'),
 (469,'Gabon\n'),
 (470,'Gambia\n'),
 (471,'Georgia\n'),
 (472,'Germany\n'),
 (473,'Gibraltar\n'),
 (474,'Greenland\n'),
 (475,'Grenada\n'),
 (476,'Guadeloupe\n'),
 (477,'Guam\n'),
 (478,'Guatemala\n'),
 (479,'Guinea\n'),
 (480,'Guinea-Bissau\n'),
 (481,'Guyana\n'),
 (482,'Haiti\n'),
 (483,'Honduras\n'),
 (484,'Hong Kong\n'),
 (485,'Hungary\n'),
 (486,'Iceland\n'),
 (487,'India\n'),
 (488,'Ireland\n'),
 (489,'Iraq\n'),
 (490,'Israel\n'),
 (491,'Italy\n'),
 (492,'Jamaica\n'),
 (493,'Japan\n'),
 (494,'Jordan\n'),
 (495,'Kazakhstan\n'),
 (496,'Kiribati\n'),
 (497,'Kuwait\n'),
 (498,'Kyrgyzstan\n'),
 (499,'Laos\n'),
 (500,'Latvia\n'),
 (501,'Lebanon\n'),
 (502,'Lesotho\n'),
 (503,'Liberia\n'),
 (504,'Libya\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (505,'Lichtenstein\n'),
 (506,'Lithuania\n'),
 (507,'Luxembourg\n'),
 (508,'Macau\n'),
 (509,'Macedonia\n'),
 (510,'Madagascar\n'),
 (511,'Malawi\n'),
 (512,'Maldives\n'),
 (513,'Mali\n'),
 (514,'Malta\n'),
 (515,'Marshall Islands\n'),
 (516,'Martinique\n'),
 (517,'Mauritius\n'),
 (518,'Mauuritania\n'),
 (519,'Mexico\n'),
 (520,'Moldava\n'),
 (521,'Monaco\n'),
 (522,'Mongolia\n'),
 (523,'Montenegro\n'),
 (524,'Monteserrat\n'),
 (525,'Morocco\n'),
 (526,'Mozambique\n'),
 (527,'Namibia\n'),
 (528,'Nauru\n'),
 (529,'Nepal\n'),
 (530,'Netherlands\n'),
 (531,'Netherlands Antilles\n'),
 (532,'New Caledonia\n'),
 (533,'New Zealand\n'),
 (534,'Nicaragua\n'),
 (535,'Niger\n'),
 (536,'Norfolk Island\n'),
 (537,'Norway\n'),
 (538,'Oman\n'),
 (539,'Pakistan\n'),
 (540,'Palau\n'),
 (541,'Panama\n'),
 (542,'Papua New Guinea\n'),
 (543,'Paraguay\n'),
 (544,'Peoples Rep of China\n'),
 (545,'Peru\n'),
 (546,'Phillipines\n'),
 (547,'Poland\n'),
 (548,'Portugal\n'),
 (549,'Puerto Rico\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (550,'Qatar\n'),
 (551,'Rawanda\n'),
 (552,'Republic of Congo\n'),
 (553,'Republic of Yemen\n'),
 (554,'Reunion\n'),
 (555,'Romania\n'),
 (556,'San Marino\n'),
 (557,'Saudi Arabia\n'),
 (558,'Senegal\n'),
 (559,'Serbia\n'),
 (560,'Seychelles\n'),
 (561,'Sierra Leone\n'),
 (562,'Singapore\n'),
 (563,'Slovakia\n'),
 (564,'Slovenia\n'),
 (565,'Solomon Islands\n'),
 (566,'South Africa\n'),
 (567,'South Korea\n'),
 (568,'Spain\n'),
 (569,'Sri Lanka\n'),
 (570,'St.Barthelemy\n'),
 (571,'St.Kitts & Nevis\n'),
 (572,'St Helena\n'),
 (573,'St.Lucia\n'),
 (574,'St.Vincent&Grenadine\n'),
 (575,'Sudan\n'),
 (576,'Suriname\n'),
 (577,'Swaziland\n'),
 (578,'Sweeden\n'),
 (579,'Switzerland\n'),
 (580,'Taiwan\n'),
 (581,'Tajikistan\n'),
 (582,'Tanzania\n'),
 (583,'Thailand\n'),
 (584,'Togo\n'),
 (585,'Tonga\n'),
 (586,'Trinidad & Tobago\n'),
 (587,'Tunisia\n'),
 (588,'Turkey\n'),
 (589,'Turks & Caicos Isles\n'),
 (590,'Turkmenistam\n'),
 (591,'Tuvalu\n'),
 (592,'Ukraine\n');
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (593,'United Arab Emirates\n'),
 (594,'United Kingdom\n'),
 (595,'United States\n'),
 (596,'Uruguay\n'),
 (597,'US Virgin Islands\n'),
 (598,'Uzbekistan\n'),
 (599,'Vanuatu\n'),
 (600,'Vatican City\n'),
 (601,'Venezuela\n'),
 (602,'Wallis & Futuna Is\n'),
 (603,'Western Samoa\n'),
 (604,'Zambia\n');
/*!40000 ALTER TABLE `nazioni` ENABLE KEYS */;


--
-- Definition of table `paesi`
--

DROP TABLE IF EXISTS `paesi`;
CREATE TABLE `paesi` (
  `id_paese` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `CAP` varchar(45) NOT NULL COMMENT 'codice d''avviamento postale',
  `fk_nazione` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_paese`),
  KEY `FK_paesi_1` (`fk_nazione`),
  CONSTRAINT `FK_paesi_1` FOREIGN KEY (`fk_nazione`) REFERENCES `nazioni` (`id_nazione`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COMMENT='Contiene tutti i paesi in cui c''è una filiale';

--
-- Dumping data for table `paesi`
--

/*!40000 ALTER TABLE `paesi` DISABLE KEYS */;
INSERT INTO `paesi` (`id_paese`,`nome`,`CAP`,`fk_nazione`) VALUES 
 (3,'Locarno','6600',180),
 (11,'Roma','0001',92),
 (12,'Milano','0002',92),
 (15,'Bellinzona','6600',180),
 (16,'Lugano','6900',180);
/*!40000 ALTER TABLE `paesi` ENABLE KEYS */;


--
-- Definition of table `pagine`
--

DROP TABLE IF EXISTS `pagine`;
CREATE TABLE `pagine` (
  `id_pagina` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(45) NOT NULL,
  PRIMARY KEY (`id_pagina`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pagine`
--

/*!40000 ALTER TABLE `pagine` DISABLE KEYS */;
INSERT INTO `pagine` (`id_pagina`,`url`) VALUES 
 (1,'home'),
 (2,'amministrazione'),
 (3,'statistiche'),
 (4,'utente');
/*!40000 ALTER TABLE `pagine` ENABLE KEYS */;


--
-- Definition of table `saldi`
--

DROP TABLE IF EXISTS `saldi`;
CREATE TABLE `saldi` (
  `fk_dipendente` int(10) unsigned NOT NULL COMMENT 'id del dipendente',
  `saldo` float NOT NULL DEFAULT '0' COMMENT 'saldo ore',
  `saldo_strd` float NOT NULL DEFAULT '0' COMMENT 'saldo ore straordinarie',
  `vac_spt` float NOT NULL DEFAULT '25' COMMENT 'vacanze spettanti',
  `vac_rst` float NOT NULL DEFAULT '25' COMMENT 'vacanze restanti',
  `vac_matr` float NOT NULL DEFAULT '0' COMMENT 'vacanze maturate',
  `percLavorativa` int(10) unsigned NOT NULL,
  PRIMARY KEY (`fk_dipendente`),
  KEY `fk_saldi_1` (`fk_dipendente`),
  CONSTRAINT `fk_saldi_1` FOREIGN KEY (`fk_dipendente`) REFERENCES `dipendenti` (`id_dipendente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Relazionata 1:1 con dipendenti contiene i saldi orari di ogn';

--
-- Dumping data for table `saldi`
--

/*!40000 ALTER TABLE `saldi` DISABLE KEYS */;
INSERT INTO `saldi` (`fk_dipendente`,`saldo`,`saldo_strd`,`vac_spt`,`vac_rst`,`vac_matr`,`percLavorativa`) VALUES 
 (10,-335.15,0,25,18,1,100),
 (11,-304,0,25,22,2,0),
 (21,0,0,25,23,0,0),
 (22,0,0,25,25,0,0),
 (23,0,0,2,0,0,0),
 (24,0,0,25,25,0,0);
/*!40000 ALTER TABLE `saldi` ENABLE KEYS */;


--
-- Definition of table `timbrature`
--

DROP TABLE IF EXISTS `timbrature`;
CREATE TABLE `timbrature` (
  `id_timbratura` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` varchar(45) DEFAULT NULL,
  `stato` varchar(1) NOT NULL DEFAULT 'E' COMMENT 'E = entrata, U = uscita',
  `fk_dipendente` int(10) unsigned NOT NULL COMMENT 'id del dipendente che ha timbrato',
  PRIMARY KEY (`id_timbratura`),
  KEY `FK_timbrature_1` (`fk_dipendente`),
  CONSTRAINT `FK_timbrature_1` FOREIGN KEY (`fk_dipendente`) REFERENCES `dipendenti` (`id_dipendente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='Contiene tutte le timbrature in entrata e in uscita che  i d';

--
-- Dumping data for table `timbrature`
--

/*!40000 ALTER TABLE `timbrature` DISABLE KEYS */;
INSERT INTO `timbrature` (`id_timbratura`,`data`,`stato`,`fk_dipendente`) VALUES 
 (1,'1264576828','E',10),
 (2,'1264610127','U',10),
 (3,'1264663228','E',10),
 (4,'1264696527','U',10),
 (5,'1264749628','E',10),
 (6,'1264782927','U',10),
 (7,'1264836028','E',10),
 (8,'1264869327','U',10),
 (9,'1264577828','E',10),
 (10,'1264577128','U',10);
/*!40000 ALTER TABLE `timbrature` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
