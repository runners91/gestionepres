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
  PRIMARY KEY (`id_dipendente`),
  UNIQUE KEY `unique_username` (`username`),
  KEY `FK_dipendenti_1` (`fk_filiale`),
  CONSTRAINT `FK_dipendenti_1` FOREIGN KEY (`fk_filiale`) REFERENCES `filiali` (`id_filiale`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 COMMENT='Contiene tutti i dati che riguardano i dipendenti';

--
-- Dumping data for table `dipendenti`
--

/*!40000 ALTER TABLE `dipendenti` DISABLE KEYS */;
INSERT INTO `dipendenti` (`id_dipendente`,`nome`,`cognome`,`username`,`password`,`fk_filiale`,`email`,`stato_att`,`commento_stato`) VALUES 
 (10,'admin','admin','admin','21232f297a57a5a743894a0e4a801fc3',1,'admin@gmail.com',1,''),
 (11,'user1','g1','u1g1','e10adc3949ba59abbe56e057f20f883e',1,'u1g1@gmail.com',1,''),
 (12,'user2','g1','u2g1','c4ca4238a0b923820dcc509a6f75849b',1,'u2g1@gmail.com',2,''),
 (14,'user3','g1','u3g1','7df656af4efecd9b1f69f708e6903b78',1,'u3g1@gmail.com',2,''),
 (15,'user1','g2','u1g2','7df656af4efecd9b1f69f708e6903b78',1,'u1g2@gmail.com',1,''),
 (16,'user2','g2','u2g2','7df656af4efecd9b1f69f708e6903b78',1,'u2g2@gmail.com',2,''),
 (17,'user3','g2','u3g2','7df656af4efecd9b1f69f708e6903b78',1,'u3g2@gmail.com',1,''),
 (18,'utente','libero','utente','e10adc3949ba59abbe56e057f20f883e',1,'utente@gmail.com',2,''),
 (19,'test1','test2','test12','7df656af4efecd9b1f69f708e6903b78',1,'te@ie.ci',1,''),
 (20,'rrgegt','tgergt','trrtg','7df656af4efecd9b1f69f708e6903b78',1,'gt@fh.tj',2,''),
 (21,'iolo','lolio','oliol','7df656af4efecd9b1f69f708e6903b78',1,'ii@tt.tt',1,'');
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
 (18,18);
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
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eventi`
--

/*!40000 ALTER TABLE `eventi` DISABLE KEYS */;
INSERT INTO `eventi` (`id_evento`,`data_da`,`data_a`,`priorita`,`commento`,`fk_dipendente`,`fk_causale`,`stato`,`commento_segnalazione`,`durata`) VALUES 
 (122,'1263337200','1263337200',1,'',11,2,'2','c','G'),
 (124,'1263337200','1263337200',1,'',12,1,'2',NULL,'G'),
 (125,'1263337200','1263337200',2,'',14,2,'2',NULL,'G'),
 (126,'1263337200','1263337200',1,'',15,1,'2',NULL,'G'),
 (133,'1263510000','1263510000',1,'',12,5,'2','','G'),
 (143,'1263337200','1263337200',3,'',10,1,'2','asd','G'),
 (144,'1263855600','1263942000',1,'',10,4,'2','dal 19.01','G'),
 (145,'1263942000','1263942000',3,'',11,4,'2','','G'),
 (146,'1264028400','1264028400',3,'',11,6,'2',NULL,'G'),
 (147,'1264114800','1264114800',3,'',11,2,'2','','G'),
 (148,'1264719600','1264719600',1,'',17,4,'2',NULL,'G'),
 (155,'1266706800','1266706800',1,'',12,4,'2',NULL,'G'),
 (158,'1266361200','1266361200',1,'',20,5,'2',NULL,'G'),
 (159,'1266361200','1266361200',1,'',19,6,'2',NULL,'G'),
 (161,'1266274800','1266274800',1,'',11,4,'2',NULL,'M'),
 (163,'1266015600','1266188400',1,'',11,1,'2',NULL,'G');
INSERT INTO `eventi` (`id_evento`,`data_da`,`data_a`,`priorita`,`commento`,`fk_dipendente`,`fk_causale`,`stato`,`commento_segnalazione`,`durata`) VALUES 
 (170,'1267657200','1267830000',1,'',11,2,'2','','G'),
 (171,'1268694000','1268866800',1,'',11,4,'2','','G'),
 (172,'1266793200','1266793200',1,'',10,4,'2',NULL,'M'),
 (173,'1266966000','1266966000',1,'',10,3,'2',NULL,'P'),
 (174,'1266361200','1266361200',1,'',11,3,'1',NULL,'G');
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
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1 COMMENT='Contiene tutti i giorni festivi che l''azienda riconosce';

--
-- Dumping data for table `festivi`
--

/*!40000 ALTER TABLE `festivi` DISABLE KEYS */;
INSERT INTO `festivi` (`id_festivo`,`nome`,`data`,`durata`,`ricorsivo`) VALUES 
 (43,'MartedÃ¬ grasso','1266274800','P',0),
 (45,'prova','1266534000','G',0),
 (46,'prova M','1266966000','M',0),
 (47,'prova P','1266793200','P',0);
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
 (1,45),
 (1,46),
 (1,47);
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
  PRIMARY KEY (`id_filiale`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `filiali`
--

/*!40000 ALTER TABLE `filiali` DISABLE KEYS */;
INSERT INTO `filiali` (`id_filiale`,`nome`,`indirizzo`,`telefono`,`fk_paese`) VALUES 
 (1,'Bellinzona','a','a',1),
 (2,'Lugano','a','a',1),
 (3,'Locarno','','a',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Contiene tutte le nazioni dove c''è una filiale';

--
-- Dumping data for table `nazioni`
--

/*!40000 ALTER TABLE `nazioni` DISABLE KEYS */;
INSERT INTO `nazioni` (`id_nazione`,`nome`) VALUES 
 (1,'Svizzera'),
 (2,'Italia'),
 (3,'Germania'),
 (4,'Francia'),
 (5,'Spagna'),
 (6,'Inghilterra');
/*!40000 ALTER TABLE `nazioni` ENABLE KEYS */;


--
-- Definition of table `paesi`
--

DROP TABLE IF EXISTS `paesi`;
CREATE TABLE `paesi` (
  `id_paese` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `codice_avv` varchar(45) NOT NULL COMMENT 'codice d''avviamento postale',
  `fk_nazione` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_paese`),
  KEY `FK_paesi_1` (`fk_nazione`),
  CONSTRAINT `FK_paesi_1` FOREIGN KEY (`fk_nazione`) REFERENCES `nazioni` (`id_nazione`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Contiene tutti i paesi in cui c''è una filiale';

--
-- Dumping data for table `paesi`
--

/*!40000 ALTER TABLE `paesi` DISABLE KEYS */;
INSERT INTO `paesi` (`id_paese`,`nome`,`codice_avv`,`fk_nazione`) VALUES 
 (1,'Bellinzona','6500',1),
 (2,'Lugano','6700',1),
 (3,'Locarno','6600',1),
 (4,'Roma','0001',2),
 (5,'Milano','0002',2);
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
  PRIMARY KEY (`fk_dipendente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Relazionata 1:1 con dipendenti contiene i saldi orari di ogn';

--
-- Dumping data for table `saldi`
--

/*!40000 ALTER TABLE `saldi` DISABLE KEYS */;
INSERT INTO `saldi` (`fk_dipendente`,`saldo`,`saldo_strd`,`vac_spt`,`vac_rst`,`vac_matr`) VALUES 
 (3,3.05,4,25,15,0),
 (10,1.2,0,25,10,1),
 (11,4,0,25,22,2),
 (21,0,0,25,25,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COMMENT='Contiene tutte le timbrature in entrata e in uscita che  i d';

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
