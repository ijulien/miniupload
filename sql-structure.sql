-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Host: databank.ijulien.com
-- Generation Time: Jan 24, 2012 at 11:19 PM
-- Server version: 5.1.53
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `cloud_ijulien_com`
--

-- --------------------------------------------------------

--
-- Table structure for table `drop_file`
--

CREATE TABLE IF NOT EXISTS `drop_file` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `salt` text NOT NULL,
  `name` text NOT NULL,
  `size` bigint(20) NOT NULL,
  `IP` text NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expire_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;
