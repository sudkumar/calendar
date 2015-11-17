-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2014 at 04:14 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

CREATE DATABASE IF NOT EXISTS `cal`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cal`
--
USE `cal`;
-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
`id` int(32) NOT NULL,
  `course` varchar(32) NOT NULL DEFAULT 'not set',
  `title` varchar(32) NOT NULL DEFAULT 'not set',
  `date` varchar(32) NOT NULL DEFAULT 'not set',
  `venue` varchar(32) NOT NULL DEFAULT 'not set',
  `fromTime` varchar(32) NOT NULL DEFAULT 'not set',
  `toTime` varchar(32) NOT NULL DEFAULT 'not set',
  `owner` varchar(32) NOT NULL,
  `time` varchar(32) NOT NULL DEFAULT 'not set'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `studentscourse`
--

CREATE TABLE IF NOT EXISTS `studentscourse` (
`id` int(32) NOT NULL,
  `userName` varchar(32) NOT NULL,
  `course` varchar(32) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- Table structure for table `studentscourseeventseen`
--

CREATE TABLE IF NOT EXISTS `studentscourseeventseen` (
`id` int(32) NOT NULL,
  `userName` varchar(32) NOT NULL,
  `course` varchar(10) NOT NULL,
  `eventId` int(32) NOT NULL,
  `seen` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `teacherscourse`
--

CREATE TABLE IF NOT EXISTS `teacherscourse` (
`id` int(32) NOT NULL,
  `userName` varchar(32) NOT NULL,
  `course` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `usersdetail`
--

CREATE TABLE IF NOT EXISTS `usersdetail` (
`user_id` int(32) NOT NULL,
  `userName` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `department` varchar(32) NOT NULL,
  `designation` varchar(32) NOT NULL,
  `flag` int(2) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `studentscourse`
--
ALTER TABLE `studentscourse`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `studentscourseeventseen`
--
ALTER TABLE `studentscourseeventseen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacherscourse`
--
ALTER TABLE `teacherscourse`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usersdetail`
--
ALTER TABLE `usersdetail`
 ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `studentscourse`
--
ALTER TABLE `studentscourse`
MODIFY `id` int(32) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `studentscourseeventseen`
--
ALTER TABLE `studentscourseeventseen`
MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `teacherscourse`
--
ALTER TABLE `teacherscourse`
MODIFY `id` int(32) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `usersdetail`
--
ALTER TABLE `usersdetail`
MODIFY `user_id` int(32) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
