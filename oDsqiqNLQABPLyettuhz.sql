-- phpMyAdmin SQL Dump
-- http://www.phpmyadmin.net
--
-- 生成日期: 2013 年 04 月 06 日 16:20

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `oDsqiqNLQABPLyettuhz`
--

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `time` date NOT NULL,
  `author` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `origin` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `accessory` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
