-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.30-community


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
-- Definition of table `assenze`
--

DROP TABLE IF EXISTS `assenze`;
CREATE TABLE `assenze` (
  `id_assenza` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'data dell''assenza',
  `quantita` float NOT NULL COMMENT '0.5 = mezza giornata, 1 = un giorno',
  `fk_dipendente` int(10) unsigned NOT NULL COMMENT 'id del dipendente assentato',
  `fk_motivo` int(10) unsigned NOT NULL COMMENT 'id dalla tabella motivi (del motivo dell''assenza)',
  PRIMARY KEY (`id_assenza`),
  KEY `FK_assenze_1` (`fk_dipendente`),
  KEY `FK_assenze_2` (`fk_motivo`),
  CONSTRAINT `FK_assenze_1` FOREIGN KEY (`fk_dipendente`) REFERENCES `dipendenti` (`id_dipendente`),
  CONSTRAINT `FK_assenze_2` FOREIGN KEY (`fk_motivo`) REFERENCES `motivi` (`id_motivo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Contiene tutte le possibili assenze che avvengono in azienda';

--
-- Dumping data for table `assenze`
--

/*!40000 ALTER TABLE `assenze` DISABLE KEYS */;
INSERT INTO `assenze` (`id_assenza`,`data`,`quantita`,`fk_dipendente`,`fk_motivo`) VALUES 
 (1,'0000-00-00 00:00:00',0.5,3,1),
 (2,'0000-00-00 00:00:00',1,4,3);
/*!40000 ALTER TABLE `assenze` ENABLE KEYS */;


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
  PRIMARY KEY (`id_dipendente`),
  UNIQUE KEY `unique_username` (`username`),
  KEY `FK_dipendenti_1` (`fk_filiale`),
  CONSTRAINT `FK_dipendenti_1` FOREIGN KEY (`fk_filiale`) REFERENCES `filiali` (`id_filiale`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COMMENT='Contiene tutti i dati che riguardano i dipendenti';

--
-- Dumping data for table `dipendenti`
--

/*!40000 ALTER TABLE `dipendenti` DISABLE KEYS */;
INSERT INTO `dipendenti` (`id_dipendente`,`nome`,`cognome`,`username`,`password`,`fk_filiale`) VALUES 
 (3,'Pinco','Pallino','pinco','e10adc3949ba59abbe56e057f20f883e',5),
 (4,'Bryan','Daepp','daepp','e10adc3949ba59abbe56e057f20f883e',3),
 (5,'Ethan','Winiger','ethan','e10adc3949ba59abbe56e057f20f883e',4);
/*!40000 ALTER TABLE `dipendenti` ENABLE KEYS */;


--
-- Definition of table `festivi`
--

DROP TABLE IF EXISTS `festivi`;
CREATE TABLE `festivi` (
  `id_festivo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'data di cadenza del festivo',
  `quantita` float NOT NULL COMMENT '0.5 = mezza giornata 1 = un giorno',
  PRIMARY KEY (`id_festivo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Contiene tutti i giorni festivi che l''azienda riconosce';

--
-- Dumping data for table `festivi`
--

/*!40000 ALTER TABLE `festivi` DISABLE KEYS */;
INSERT INTO `festivi` (`id_festivo`,`nome`,`data`,`quantita`) VALUES 
 (1,'santo Stefano','0000-00-00 00:00:00',1),
 (2,'san Giuseppe','0000-00-00 00:00:00',0.5);
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
 (3,1),
 (4,2);
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `filiali`
--

/*!40000 ALTER TABLE `filiali` DISABLE KEYS */;
/*!40000 ALTER TABLE `filiali` ENABLE KEYS */;


--
-- Definition of table `motivi`
--

DROP TABLE IF EXISTS `motivi`;
CREATE TABLE `motivi` (
  `id_motivo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `descrizione` varchar(4000) NOT NULL,
  PRIMARY KEY (`id_motivo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Contiene tutti i vari motivi di assenza che l''azienda preved';

--
-- Dumping data for table `motivi`
--

/*!40000 ALTER TABLE `motivi` DISABLE KEYS */;
INSERT INTO `motivi` (`id_motivo`,`nome`,`descrizione`) VALUES 
 (1,'MALATTIA','Quando un dipendente è malato'),
 (2,'CONGEDO','In caso di matrimoni, funerali, traslochi, ecc...'),
 (3,'SCUOLA','In caso di presenza scolastica del dipendente'),
 (4,'VACANZA','In caso di vacanza del dipendente'),
 (5,'VACANZA 0.5','In caso di mezza giornata di vacanza del dipendente');
/*!40000 ALTER TABLE `motivi` ENABLE KEYS */;


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
-- Definition of table `saldi`
--

DROP TABLE IF EXISTS `saldi`;
CREATE TABLE `saldi` (
  `fk_dipendente` int(10) unsigned NOT NULL COMMENT 'id del dipendente',
  `saldo` float NOT NULL COMMENT 'saldo ore',
  `saldo_strd` float NOT NULL COMMENT 'saldo ore straordinarie',
  `vac_spt` float NOT NULL COMMENT 'vacanze spettanti',
  `var_rst` float NOT NULL COMMENT 'vacanze restanti',
  `vac_matr` float NOT NULL COMMENT 'vacanze maturate',
  PRIMARY KEY (`fk_dipendente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Relazionata 1:1 con dipendenti contiene i saldi orari di ogn';

--
-- Dumping data for table `saldi`
--

/*!40000 ALTER TABLE `saldi` DISABLE KEYS */;
INSERT INTO `saldi` (`fk_dipendente`,`saldo`,`saldo_strd`,`vac_spt`,`var_rst`,`vac_matr`) VALUES 
 (3,3.05,4,25,15,0),
 (4,1.2,0,25,10,1);
/*!40000 ALTER TABLE `saldi` ENABLE KEYS */;


--
-- Definition of table `timbrature`
--

DROP TABLE IF EXISTS `timbrature`;
CREATE TABLE `timbrature` (
  `id_timbratura` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stato` varchar(1) NOT NULL DEFAULT 'E' COMMENT 'E = entrata, U = uscita',
  `fk_dipendente` int(10) unsigned NOT NULL COMMENT 'id del dipendente che ha timbrato',
  PRIMARY KEY (`id_timbratura`),
  KEY `FK_timbrature_1` (`fk_dipendente`),
  CONSTRAINT `FK_timbrature_1` FOREIGN KEY (`fk_dipendente`) REFERENCES `dipendenti` (`id_dipendente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Contiene tutte le timbrature in entrata e in uscita che  i d';

--
-- Dumping data for table `timbrature`
--

/*!40000 ALTER TABLE `timbrature` DISABLE KEYS */;
INSERT INTO `timbrature` (`id_timbratura`,`data`,`stato`,`fk_dipendente`) VALUES 
 (1,'0000-00-00 00:00:00','E',3),
 (2,'0000-00-00 00:00:00','U',3),
 (3,'0000-00-00 00:00:00','E',5),
 (4,'0000-00-00 00:00:00','U',5);
/*!40000 ALTER TABLE `timbrature` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
