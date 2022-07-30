-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 28, 2022 alle 09:47
-- Versione del server: 5.6.33-log
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_zifiorino`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `backupDati`
--

CREATE TABLE IF NOT EXISTS `backupDati` (
  `utente` varchar(100) NOT NULL,
  `idBackup` int(11) NOT NULL,
  `PBackup` varchar(700) NOT NULL,
  `datebackup` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`utente`,`idBackup`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `siti`
--

CREATE TABLE IF NOT EXISTS `siti` (
  `nome_sito` varchar(100) NOT NULL,
  `utente_sito` varchar(700) NOT NULL,
  `passw_sito` varchar(700) NOT NULL,
  `note` varchar(1600) DEFAULT NULL,
  `id` varchar(100) NOT NULL,
  PRIMARY KEY (`id`,`nome_sito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Struttura della tabella `utenti`
--

CREATE TABLE IF NOT EXISTS `utenti` (
  `utente` varchar(100) NOT NULL,
  `passw` varchar(700) NOT NULL,
  `ultimaPassw` date NOT NULL,
  `ultimoBackup` date DEFAULT NULL,
  PRIMARY KEY (`utente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
