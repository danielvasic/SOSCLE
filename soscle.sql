-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 18, 2012 at 02:03 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `soscle`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `za` int(11) NOT NULL,
  `od` int(11) NOT NULL,
  `poruka` text COLLATE utf8_unicode_ci NOT NULL,
  `vrijeme` datetime NOT NULL,
  `vidjeno` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `za` (`za`),
  KEY `od` (`od`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum`
--

CREATE TABLE IF NOT EXISTS `forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_korisnika` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `status` enum('otkljucan','zakljucan') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'otkljucan',
  `ime` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `opis` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_korisnika` (`id_korisnika`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `grupa`
--

CREATE TABLE IF NOT EXISTS `grupa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ime` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `opis` text COLLATE utf8_unicode_ci NOT NULL,
  `vrijeme` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `grupa_forum`
--

CREATE TABLE IF NOT EXISTS `grupa_forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_foruma` int(11) NOT NULL,
  `id_grupe` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_foruma` (`id_foruma`),
  KEY `id_grupe` (`id_grupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `grupa_kolegij`
--

CREATE TABLE IF NOT EXISTS `grupa_kolegij` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_grupe` int(11) NOT NULL,
  `id_kolegija` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_grupe` (`id_grupe`),
  KEY `id_kolegija` (`id_kolegija`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `grupa_korisnik`
--

CREATE TABLE IF NOT EXISTS `grupa_korisnik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_grupe` int(11) NOT NULL,
  `id_korisnika` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_grupe` (`id_grupe`),
  KEY `id_korisnika` (`id_korisnika`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `kolegij`
--

CREATE TABLE IF NOT EXISTS `kolegij` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ime` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `opis` text COLLATE utf8_unicode_ci NOT NULL,
  `id_korisnika` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_korisnika` (`id_korisnika`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE IF NOT EXISTS `korisnik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ime` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `prezime` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `puno_ime` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `lozinka` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `grad` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `opis` text COLLATE utf8_unicode_ci,
  `uloga` enum('Ucenik','Ucitelj','Administrator') COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zadnja_aktivnost` datetime DEFAULT NULL,
  `status` enum('online','offline','zauzet') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pokusaj`
--

CREATE TABLE IF NOT EXISTS `pokusaj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_korisnika` int(11) NOT NULL,
  `id_sadrzaja` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_korisnika` (`id_korisnika`),
  KEY `id_sadrzaja` (`id_sadrzaja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_korisnika` int(11) NOT NULL,
  `id_teme` int(11) NOT NULL,
  `id_foruma` int(11) NOT NULL,
  `id_roditelja` int(11) DEFAULT '0',
  `ime` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `sadrzaj` text COLLATE utf8_unicode_ci NOT NULL,
  `vrijeme` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_korisnika` (`id_korisnika`),
  KEY `id_teme` (`id_teme`),
  KEY `id_foruma` (`id_foruma`),
  KEY `id_roditelja` (`id_roditelja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sadrzaj`
--

CREATE TABLE IF NOT EXISTS `sadrzaj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kolegija` int(11) NOT NULL,
  `id_korisnika` int(11) NOT NULL,
  `ime` varchar(25) NOT NULL,
  `opis` text NOT NULL,
  `vrsta_navigacije` enum('nextprev','tree','both') NOT NULL,
  `putanja` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_kolegija` (`id_kolegija`),
  KEY `id_korisnika` (`id_korisnika`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scormvarijable`
--

CREATE TABLE IF NOT EXISTS `scormvarijable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sco_id` varchar(250) NOT NULL,
  `sco_title` varchar(250) NOT NULL,
  `element` varchar(75) NOT NULL,
  `vrijednost` text,
  `id_pokusaja` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pokusaja` (`id_pokusaja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tema`
--

CREATE TABLE IF NOT EXISTS `tema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_korisnika` int(11) NOT NULL,
  `id_foruma` int(11) NOT NULL,
  `ime` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `opis` text COLLATE utf8_unicode_ci NOT NULL,
  `datum` datetime NOT NULL,
  `status` enum('otkljucan','zakljucan') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_korisnika` (`id_korisnika`),
  KEY `id_korisnika_2` (`id_korisnika`,`id_foruma`),
  KEY `id_foruma` (`id_foruma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`od`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`za`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `forum`
--
ALTER TABLE `forum`
  ADD CONSTRAINT `forum_ibfk_1` FOREIGN KEY (`id_korisnika`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grupa_forum`
--
ALTER TABLE `grupa_forum`
  ADD CONSTRAINT `grupa_forum_ibfk_4` FOREIGN KEY (`id_grupe`) REFERENCES `grupa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grupa_forum_ibfk_3` FOREIGN KEY (`id_foruma`) REFERENCES `forum` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grupa_kolegij`
--
ALTER TABLE `grupa_kolegij`
  ADD CONSTRAINT `grupa_kolegij_ibfk_2` FOREIGN KEY (`id_kolegija`) REFERENCES `grupa_kolegij` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grupa_kolegij_ibfk_1` FOREIGN KEY (`id_grupe`) REFERENCES `grupa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grupa_korisnik`
--
ALTER TABLE `grupa_korisnik`
  ADD CONSTRAINT `grupa_korisnik_ibfk_2` FOREIGN KEY (`id_korisnika`) REFERENCES `grupa_korisnik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grupa_korisnik_ibfk_1` FOREIGN KEY (`id_grupe`) REFERENCES `grupa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kolegij`
--
ALTER TABLE `kolegij`
  ADD CONSTRAINT `kolegij_ibfk_1` FOREIGN KEY (`id_korisnika`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pokusaj`
--
ALTER TABLE `pokusaj`
  ADD CONSTRAINT `pokusaj_ibfk_2` FOREIGN KEY (`id_sadrzaja`) REFERENCES `sadrzaj` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pokusaj_ibfk_1` FOREIGN KEY (`id_korisnika`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_3` FOREIGN KEY (`id_foruma`) REFERENCES `forum` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_korisnika`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`id_teme`) REFERENCES `tema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sadrzaj`
--
ALTER TABLE `sadrzaj`
  ADD CONSTRAINT `sadrzaj_ibfk_2` FOREIGN KEY (`id_korisnika`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sadrzaj_ibfk_1` FOREIGN KEY (`id_kolegija`) REFERENCES `kolegij` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `scormvarijable`
--
ALTER TABLE `scormvarijable`
  ADD CONSTRAINT `scormvarijable_ibfk_1` FOREIGN KEY (`id_pokusaja`) REFERENCES `pokusaj` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tema`
--
ALTER TABLE `tema`
  ADD CONSTRAINT `tema_ibfk_2` FOREIGN KEY (`id_foruma`) REFERENCES `forum` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tema_ibfk_1` FOREIGN KEY (`id_korisnika`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
