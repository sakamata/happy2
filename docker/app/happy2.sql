-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017 年 11 月 03 日 17:50
-- サーバのバージョン： 5.5.56-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `happy2`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `adsetting`
--

CREATE TABLE `adsetting` (
  `no` int(11) NOT NULL,
  `userViewLimitClients` int(11) NOT NULL DEFAULT '20',
  `adminTablesViewLimit` int(11) NOT NULL DEFAULT '50',
  `userDefaultPt` int(11) NOT NULL DEFAULT '100',
  `userMinPt` int(11) NOT NULL DEFAULT '50',
  `userClickPostIntervalSecond` int(11) NOT NULL DEFAULT '60'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `adsetting`
--

INSERT INTO `adsetting` (`no`, `userViewLimitClients`, `adminTablesViewLimit`, `userDefaultPt`, `userMinPt`, `userClickPostIntervalSecond`) VALUES
(1, 30, 20, 100, 50, 10);

-- --------------------------------------------------------

--
-- テーブルの構造 `adtbus`
--

CREATE TABLE `adtbus` (
  `usNo` int(11) NOT NULL,
  `usId` varchar(32) NOT NULL,
  `usPs` varchar(64) NOT NULL,
  `lastInDate` datetime DEFAULT NULL,
  `permission` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `adtbus`
--

INSERT INTO `adtbus` (`usNo`, `usId`, `usPs`, `lastInDate`, `permission`) VALUES
(1, 'admin', 'e18c2bb4880c4c47e96b10eb25aa8da735841603', NULL, NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `tbcalctime`
--

CREATE TABLE `tbcalctime` (
  `calcNo` int(11) NOT NULL,
  `calcTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `tbcalctime`
--

INSERT INTO `tbcalctime` (`calcNo`, `calcTime`) VALUES
(1, '2017-10-29 07:06:41');

-- --------------------------------------------------------

--
-- テーブルの構造 `tbfollow`
--

CREATE TABLE `tbfollow` (
  `followNo` int(11) NOT NULL,
  `usNo` int(11) DEFAULT NULL,
  `followingNo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `tbgvn`
--

CREATE TABLE `tbgvn` (
  `gvnNo` int(11) NOT NULL,
  `usNo` int(11) DEFAULT NULL,
  `seUs` int(11) DEFAULT NULL,
  `seClk` int(11) DEFAULT NULL,
  `dTm` datetime DEFAULT NULL COMMENT 'クリック数を毎回登録するためのTable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `tbgvn`
--

INSERT INTO `tbgvn` (`gvnNo`, `usNo`, `seUs`, `seClk`, `dTm`) VALUES
(1, 1, 1, 1, '2017-10-29 07:27:42'),
(2, 2, 2, 1, '2017-10-29 08:20:07'),
(3, 2, 1, 4, '2017-10-29 08:22:48'),
(4, 2, 2, 1, '2017-10-29 08:22:50'),
(5, 1, 2, 1, '2017-10-29 08:24:52'),
(6, 1, 1, 1, '2017-10-29 08:24:53'),
(7, 1, 2, 1, '2017-10-29 08:24:54'),
(8, 2, 1, 1, '2017-10-30 14:38:30'),
(9, 2, 1, 1, '2017-10-30 14:41:24'),
(10, 2, 2, 1, '2017-10-30 14:41:25'),
(11, 1, 2, 1, '2017-10-30 14:41:21'),
(12, 1, 1, 1, '2017-10-30 14:41:27'),
(13, 1, 2, 1, '2017-10-30 14:42:13');

-- --------------------------------------------------------

--
-- テーブルの構造 `tbset`
--

CREATE TABLE `tbset` (
  `setNo` int(11) NOT NULL,
  `setGvnNo` int(11) DEFAULT NULL,
  `usNo` int(11) DEFAULT NULL,
  `seUs` int(11) DEFAULT NULL,
  `getPt` decimal(18,9) DEFAULT NULL,
  `dTm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `tbus`
--

CREATE TABLE `tbus` (
  `usNo` int(11) NOT NULL,
  `usId` varchar(32) NOT NULL,
  `usPs` varchar(64) DEFAULT NULL,
  `usName` varchar(32) DEFAULT NULL,
  `usImg` varchar(256) DEFAULT NULL,
  `nowPt` decimal(18,9) DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `host` varchar(100) DEFAULT NULL,
  `regDate` datetime DEFAULT NULL,
  `facebookId` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `tbus`
--

INSERT INTO `tbus` (`usNo`, `usId`, `usPs`, `usName`, `usImg`, `nowPt`, `ip`, `host`, `regDate`, `facebookId`) VALUES
(1, 'aaaa', '7599fcea685786ffa4f9314259a155301ed24f5d', '生百糸八力四早石', 'aaaa.jpg', '100.000000000', NULL, NULL, '2017-10-29 07:27:42', NULL),
(2, 'bbbb', '4c025d615368074eb5c32f50785815fd769ec0b5', 'bbbb', 'default/dummy.png', '100.000000000', '121.103.27.215', 'p79671bd7.tkyea122.ap.so-net.ne.jp', '2017-10-29 08:20:07', NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `tb_user_status`
--

CREATE TABLE `tb_user_status` (
  `No` int(11) NOT NULL,
  `usNo` int(11) NOT NULL,
  `lastActiveTime` timestamp NULL DEFAULT NULL,
  `latitude` decimal(14,7) DEFAULT NULL,
  `longitude` decimal(14,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `tb_user_status`
--

INSERT INTO `tb_user_status` (`No`, `usNo`, `lastActiveTime`, `latitude`, `longitude`) VALUES
(1, 1, '2017-10-28 22:27:42', NULL, NULL),
(2, 2, '2017-10-28 23:20:07', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adsetting`
--
ALTER TABLE `adsetting`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `adtbus`
--
ALTER TABLE `adtbus`
  ADD PRIMARY KEY (`usNo`),
  ADD UNIQUE KEY `usNo_UNIQUE` (`usNo`),
  ADD UNIQUE KEY `usId_UNIQUE` (`usId`);

--
-- Indexes for table `tbcalctime`
--
ALTER TABLE `tbcalctime`
  ADD PRIMARY KEY (`calcNo`);

--
-- Indexes for table `tbfollow`
--
ALTER TABLE `tbfollow`
  ADD PRIMARY KEY (`followNo`);

--
-- Indexes for table `tbgvn`
--
ALTER TABLE `tbgvn`
  ADD PRIMARY KEY (`gvnNo`);

--
-- Indexes for table `tbset`
--
ALTER TABLE `tbset`
  ADD PRIMARY KEY (`setNo`),
  ADD UNIQUE KEY `setGvnNo_UNIQUE` (`setGvnNo`);

--
-- Indexes for table `tbus`
--
ALTER TABLE `tbus`
  ADD PRIMARY KEY (`usNo`),
  ADD UNIQUE KEY `id_UNIQUE` (`usNo`),
  ADD UNIQUE KEY `usId_UNIQUE` (`usId`),
  ADD UNIQUE KEY `facebookId_UNIQUE` (`facebookId`);

--
-- Indexes for table `tb_user_status`
--
ALTER TABLE `tb_user_status`
  ADD PRIMARY KEY (`No`),
  ADD UNIQUE KEY `usNo_UNIQUE` (`usNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adsetting`
--
ALTER TABLE `adsetting`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `adtbus`
--
ALTER TABLE `adtbus`
  MODIFY `usNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbcalctime`
--
ALTER TABLE `tbcalctime`
  MODIFY `calcNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbfollow`
--
ALTER TABLE `tbfollow`
  MODIFY `followNo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgvn`
--
ALTER TABLE `tbgvn`
  MODIFY `gvnNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbset`
--
ALTER TABLE `tbset`
  MODIFY `setNo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbus`
--
ALTER TABLE `tbus`
  MODIFY `usNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_user_status`
--
ALTER TABLE `tb_user_status`
  MODIFY `No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
